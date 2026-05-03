<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ParentController extends Controller
{
    public function dashboard()
    {
        $userId = Auth::id();
        $parent = DB::table('parents')->where('user_id', $userId)->first();

        if (!$parent) {
            return redirect()->route('login')->withErrors(['error' => 'Parent record not found']);
        }

        $parentName = $parent->full_name;

        return view('parent', compact('parentName'));
    }

    // API: Get parent's children
    public function getChildren()
    {
        $userId = Auth::id();
        $parent = DB::table('parents')->where('user_id', $userId)->first();

        if (!$parent) {
            return response()->json([]);
        }

        $children = DB::table('parent_student')
            ->join('students', 'parent_student.student_id', '=', 'students.id')
            ->where('parent_student.parent_id', $parent->id)
            ->select(['students.id', 'students.full_name', 'students.date_of_birth'])
            ->get();

        foreach ($children as $child) {
            $age = \Carbon\Carbon::parse($child->date_of_birth)->age;
            $child->age = $age;

            $enrollment = DB::table('enrollments')
                ->where('student_id', $child->id)
                ->where('status', 'active')
                ->first();

            if ($enrollment) {
                $section = DB::table('sections')->where('id', $enrollment->section_id)->first();
                $class = DB::table('classes')->where('id', $section->class_id)->first();
                $child->class = $class ? $class->name . ' - Section ' . $section->section_name : 'Not enrolled';
                $child->teacher = $this->getTeacherName($section->teacher_id ?? null);
            } else {
                $child->class = 'Not enrolled';
                $child->teacher = 'Not assigned';
            }
        }

        return response()->json($children);
    }


      public function getChildActivities(int $childId)
{
    // Get the child's class_id
    $enrollment = DB::table('enrollments')
        ->where('student_id', $childId)
        ->where('status', 'active')
        ->first();

    if (!$enrollment) {
        return response()->json([]);
    }

    $section = DB::table('sections')->where('id', $enrollment->section_id)->first();
    $classId = $section->class_id;

    // Get activities for THIS SPECIFIC CLASS only
    $activities = DB::table('activities')
        ->where('class_id', $classId)
        ->get();

    // Get completions for this child
    $completions = DB::table('activity_completions')
        ->where('student_id', $childId)
        ->get()
        ->keyBy('activity_id');

    $result = [];
    foreach ($activities as $activity) {
        $completion = $completions->get($activity->id);
        $status = 'Not Started';
        $completionDate = null;

        if ($completion) {
            $statusObj = DB::table('activity_completion_statuses')
                ->where('id', $completion->activity_completion_status_id)
                ->first();
            $status = $statusObj ? $statusObj->description : 'Unknown';
            $completionDate = $completion->completion_date;
        }

        $result[] = [
            'id' => $activity->id,
            'activity' => $activity->title,
            'date' => $completionDate ? date('M d, Y', strtotime($completionDate)) : '-',
            'status' => strtolower($status),
            'rating' => 0
        ];
    }

    return response()->json($result);
}

public function getChildProgress(int $childId)
{
    // Get the child's class_id from enrollment
    $enrollment = DB::table('enrollments')
        ->where('student_id', $childId)
        ->where('status', 'active')
        ->first();

    if (!$enrollment) {
        return response()->json([
            'progress' => 0,
            'completedCount' => 0,
            'avgRating' => 0,
            'totalActivities' => 0
        ]);
    }

    $section = DB::table('sections')->where('id', $enrollment->section_id)->first();
    $classId = $section->class_id;

    // Get total activities for THIS SPECIFIC CLASS only
    $totalActivities = DB::table('activities')
        ->where('class_id', $classId)
        ->count();

    // Get completed activities for this child
    $completedActivities = DB::table('activity_completions')
        ->where('student_id', $childId)
        ->where('activity_completion_status_id', 2)
        ->count();

    // Calculate progress percentage
    $progress = $totalActivities > 0 ? round(($completedActivities / $totalActivities) * 100) : 0;

    // Get average rating from assessments
    $avgRating = DB::table('assessments')
        ->where('student_id', $childId)
        ->avg('rating');

    $avgRating = $avgRating ? round($avgRating, 1) : 0;

    return response()->json([
        'progress' => $progress,
        'completedCount' => $completedActivities,
        'avgRating' => $avgRating,
        'totalActivities' => $totalActivities
    ]);
}


public function getChildActivitiesWithRatings(int $childId)
{
    // Get the child's class_id
    $enrollment = DB::table('enrollments')
        ->where('student_id', $childId)
        ->where('status', 'active')
        ->first();

    if (!$enrollment) {
        return response()->json([]);
    }

    $section = DB::table('sections')->where('id', $enrollment->section_id)->first();
    $classId = $section->class_id;

    // Get completed activities for THIS SPECIFIC CLASS only
    $completions = DB::table('activity_completions')
        ->where('student_id', $childId)
        ->where('activity_completion_status_id', 2)
        ->get();

    $activities = [];
    foreach ($completions as $completion) {
        $activity = DB::table('activities')
            ->where('id', $completion->activity_id)
            ->where('class_id', $classId)  // ← Only activities from child's class
            ->first();

        if ($activity) {
            // Get competencies with ratings (same as before)
            $competencies = DB::table('competencies')
                ->leftJoin('assessments', function($join) use ($childId) {
                    $join->on('competencies.id', '=', 'assessments.competency_id')
                         ->where('assessments.student_id', '=', $childId);
                })
                ->where('competencies.activity_id', $activity->id)
                ->select(['competencies.id', 'competencies.description as competency_name', 'assessments.rating', 'assessments.comment', 'assessments.created_at as assessed_at'])
                ->get();

            $totalRating = 0;
            $ratedCount = 0;
            foreach ($competencies as $comp) {
                if ($comp->rating !== null) {
                    $totalRating += $comp->rating;
                    $ratedCount++;
                }
            }
            $avgRating = $ratedCount > 0 ? round($totalRating / $ratedCount, 1) : 0;

            $activities[] = [
                'id' => $activity->id,
                'title' => $activity->title,
                'objective' => $activity->objective,
                'overview' => $activity->overview,
                'completion_date' => $completion->completion_date ? date('M d, Y', strtotime($completion->completion_date)) : 'N/A',
                'avg_rating' => $avgRating,
                'competencies' => $competencies,
                'total_competencies' => $competencies->count(),
                'rated_competencies' => $ratedCount
            ];
        }
    }

    return response()->json($activities);
}


    // Helper methods
    private function getTeacherName(int $teacherId)
    {
        if (!$teacherId) return 'Not assigned';
        $teacher = DB::table('teachers')->where('id', $teacherId)->first();
        return $teacher ? $teacher->full_name : 'Not assigned';
    }

    private function getRatingFromStatus(mixed $status)
    {
        switch ($status) {
            case 'completed': return 4;
            case 'in_progress': return 2;
            default: return 3;
        }
    }

    // API: Get all messages for logged-in parent
    public function getMessages()
    {
        $userId = Auth::id();
        $parent = DB::table('parents')->where('user_id', $userId)->first();

        if (!$parent) {
            return response()->json([]);
        }

        $received = DB::table('messages')
            ->where('receiver_id', $userId)
            ->where('receiver_type', 'parent')
            ->whereNull('deleted_at')
            ->orderBy('created_at', 'desc')
            ->get();

        $sent = DB::table('messages')
            ->where('sender_id', $userId)
            ->where('sender_type', 'parent')
            ->whereNull('deleted_at')
            ->orderBy('created_at', 'desc')
            ->get();

        $messages = [];

        foreach ($received as $msg) {
            $sender = $this->getUserInfo($msg->sender_id, $msg->sender_type);
            $messages[] = [
                'id' => $msg->id,
                'from' => $sender['name'],
                'from_id' => $msg->sender_id,
                'from_type' => $msg->sender_type,
                'subject' => $msg->subject,
                'message' => $msg->message,
                'date' => date('M d, Y H:i', strtotime($msg->created_at)),
                'is_read' => $msg->is_read,
                'direction' => 'received'
            ];
        }

        foreach ($sent as $msg) {
            $receiver = $this->getUserInfo($msg->receiver_id, $msg->receiver_type);
            $messages[] = [
                'id' => $msg->id,
                'to' => $receiver['name'],
                'to_id' => $msg->receiver_id,
                'to_type' => $msg->receiver_type,
                'subject' => $msg->subject,
                'message' => $msg->message,
                'date' => date('M d, Y H:i', strtotime($msg->created_at)),
                'direction' => 'sent'
            ];
        }

        usort($messages, function($a, $b) {
            return strtotime($b['date']) - strtotime($a['date']);
        });

        return response()->json($messages);
    }

    // API: Get recipients parent can message
    public function getRecipients()
    {
        $userId = Auth::id();
        $parent = DB::table('parents')->where('user_id', $userId)->first();

        if (!$parent) {
            return response()->json([]);
        }

        $studentIds = DB::table('parent_student')
            ->where('parent_id', $parent->id)
            ->pluck('student_id');

        $sectionIds = DB::table('enrollments')
            ->whereIn('student_id', $studentIds)
            ->where('status', 'active')
            ->pluck('section_id');

        $teacherIds = DB::table('sections')
            ->whereIn('id', $sectionIds)
            ->whereNotNull('teacher_id')
            ->pluck('teacher_id')
            ->unique();

        $recipients = [];
        foreach ($teacherIds as $teacherId) {
            $teacher = DB::table('teachers')->where('id', $teacherId)->first();
            if ($teacher) {
                $user = DB::table('users')->where('id', $teacher->user_id)->first();
                if ($user) {
                    $recipients[] = [
                        'id' => $user->id,
                        'name' => $teacher->full_name,
                        'type' => 'teacher'
                    ];
                }
            }
        }

        $coordinator = DB::table('users')->where('role_id', 1)->first();
        if ($coordinator) {
            $recipients[] = [
                'id' => $coordinator->id,
                'name' => 'Coordinator',
                'type' => 'coordinator'
            ];
        }

        return response()->json($recipients);
    }

        public function sendMessage(Request $request)
{
    try {
        $userId = Auth::id();
        $parent = DB::table('parents')->where('user_id', $userId)->first();

        if (!$parent) {
            return response()->json(['success' => false, 'message' => 'Parent not found']);
        }

        DB::table('messages')->insert([
            'sender_id' => $userId,
            'sender_type' => 'parent',
            'receiver_id' => $request->receiver_id,
            'receiver_type' => $request->receiver_type,
            'subject' => $request->subject,  // Can be null
            'message' => $request->message,
            'is_read' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json(['success' => true, 'message' => 'Message sent successfully!']);

    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => $e->getMessage()]);
    }
}

    // Helper for parent controller
    private function getUserInfo(int $userId,string $type)
    {
        $user = DB::table('users')->where('id', $userId)->first();
        $name = $user->email;

        if ($type == 'teacher') {
            $teacher = DB::table('teachers')->where('user_id', $userId)->first();
            $name = $teacher ? $teacher->full_name : $user->email;
        } elseif ($type == 'parent') {
            $parent = DB::table('parents')->where('user_id', $userId)->first();
            $name = $parent ? $parent->full_name : $user->email;
        } elseif ($type == 'coordinator') {
            $name = 'Coordinator';
        }

        return ['id' => $userId, 'name' => $name, 'type' => $type];
    }

    // API: Mark message as read
    public function markAsRead(int $id)
    {
        try {
            DB::table('messages')->where('id', $id)->update([
                'is_read' => true,
                'updated_at' => now()
            ]);
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false]);
        }
    }

    // API: Get full conversation between parent and a specific participant (teacher or coordinator)
public function getConversation(int $participantId)
{
    $userId = Auth::id();
    $parent = DB::table('parents')->where('user_id', $userId)->first();

    if (!$parent) {
        return response()->json([]);
    }

    // Get the participant's type
    $participant = DB::table('users')->where('id', $participantId)->first();
    $participantType = $participant->role_id == 2 ? 'teacher' : ($participant->role_id == 3 ? 'parent' : 'coordinator');

    // Get all messages between this parent and the specific participant
    $messages = DB::table('messages')
        ->where(function($query) use ($userId, $participantId, $participantType) {
            $query->where(function($q) use ($userId, $participantId, $participantType) {
                $q->where('sender_id', $userId)
                  ->where('sender_type', 'parent')
                  ->where('receiver_id', $participantId)
                  ->where('receiver_type', $participantType);
            })->orWhere(function($q) use ($userId, $participantId, $participantType) {
                $q->where('sender_id', $participantId)
                  ->where('sender_type', $participantType)
                  ->where('receiver_id', $userId)
                  ->where('receiver_type', 'parent');
            });
        })
        ->whereNull('deleted_at')
        ->orderBy('created_at', 'asc')
        ->get();

    $conversation = [];
    foreach ($messages as $msg) {
        if ($msg->sender_type == 'parent') {
            $sender = $parent->full_name;
            $sender_type = 'parent';
        } elseif ($msg->sender_type == 'teacher') {
            $teacher = DB::table('teachers')->where('user_id', $msg->sender_id)->first();
            $sender = $teacher ? $teacher->full_name : 'Teacher';
            $sender_type = 'teacher';
        } else {
            $sender = 'Coordinator';
            $sender_type = 'coordinator';
        }

        $conversation[] = [
            'id' => $msg->id,
            'sender' => $sender,
            'sender_type' => $sender_type,
            'message' => $msg->message,
            'date' => date('M d, Y H:i', strtotime($msg->created_at)),
            'is_read' => $msg->is_read
        ];

        // Mark as read if parent is the receiver
        if ($msg->receiver_id == $userId && !$msg->is_read) {
            DB::table('messages')->where('id', $msg->id)->update(['is_read' => true]);
        }
    }

    return response()->json($conversation);
}

    // API: Reply to a message
    public function replyToMessage(Request $request)
    {
        try {
            $userId = Auth::id();
            $parent = DB::table('parents')->where('user_id', $userId)->first();

            if (!$parent) {
                return response()->json(['success' => false, 'message' => 'Parent not found']);
            }

             // Determine receiver type from the receiver's role_id
        $receiver = DB::table('users')->where('id', $request->receiver_id)->first();
        $receiverType = $receiver->role_id == 3 ? 'parent' : ($receiver->role_id == 2 ? 'teacher' : 'coordinator');

            DB::table('messages')->insert([
                'sender_id' => $userId,
                'sender_type' => 'parent',
                'receiver_id' => $request->receiver_id,
                'receiver_type' =>$receiverType,
                'subject' => $request->subject,
                'message' => $request->message,
                'is_read' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return response()->json(['success' => true, 'message' => 'Reply sent successfully!']);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

// Soft delete message
public function deleteMessage(int $id)
{
    try {
        $userId = Auth::id();
        $message = DB::table('messages')->where('id', $id)->first();

        if (!$message) {
            return response()->json(['success' => false, 'message' => 'Message not found']);
        }

        if ($message->sender_id != $userId) {
            return response()->json(['success' => false, 'message' => 'Cannot delete others messages']);
        }

        if ($message->deleted_at) {
            return response()->json(['success' => false, 'message' => 'Message already deleted']);
        }

        DB::table('messages')->where('id', $id)->update([
            'deleted_at' => now(),
            'updated_at' => now()
        ]);

        return response()->json(['success' => true, 'message' => 'Message deleted successfully']);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => 'Delete failed']);
    }
}

public function markMessagesAsRead(int $senderId)
{
    $userId = Auth::id();

    DB::table('messages')
        ->where('sender_id', $senderId)
        ->where('receiver_id', $userId)
        ->where('is_read', false)
        ->update(['is_read' => true]);

    return response()->json(['success' => true]);
}
public function getUnreadCount()
{
    $userId = Auth::id();

    $count = DB::table('messages')
        ->where('receiver_id', $userId)
        ->where('receiver_type', $this->getUserType())
        ->where('is_read', false)
        ->whereNull('deleted_at')
        ->count();

    return response()->json(['count' => $count]);
}
private function getUserType()
{
    // Determine based on controller
    if ($this instanceof CoordinatorController) return 'coordinator';
    if ($this instanceof TeacherController) return 'teacher';
    return 'parent';
}

public function changePassword(Request $request)
{
    try {
        // Validate request
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6',
            'confirm_password' => 'required|same:new_password'
        ]);

        // Get logged-in user
        $userId = Auth::id();
        $user = DB::table('users')->where('id', $userId)->first();

        // Verify current password
        if (!password_verify($request->current_password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Current password is incorrect'
            ]);
        }

        // Update password
        DB::table('users')->where('id', $userId)->update([
            'password' => bcrypt($request->new_password),
            'updated_at' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Password changed successfully!'
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ]);
    }
}
}


