<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\Models\Section;
use App\Models\User;
use App\Models\Activity;
use App\Models\Assessment;
use App\Models\Competency;
use App\Models\Teacher;
use App\Models\Student;

class TeacherController extends Controller
{
    public function dashboard()
    {
        // Get the logged-in teacher's user_id from session
        $userId = Auth::id();

        // Find the teacher record
        $teacher = DB::table('teachers')->where('user_id', $userId)->first();

        if (!$teacher) {
            return redirect()->route('login')->withErrors(['error' => 'Teacher record not found']);
        }

        // Get teacher's name for greeting
        $teacherName = $teacher->full_name;

        return view('teacher', compact('teacherName'));
    }



    // API: Get teacher's classes with student counts and next activities
    public function getMyClasses()
    {
        $userId = Auth::id();
        $teacher = DB::table('teachers')->where('user_id', $userId)->first();

        if (!$teacher) {
            return response()->json([]);
        }

        // Get sections where this teacher is assigned
        $sections = DB::table('sections')
            ->where('teacher_id', $teacher->id)
            ->where('is_active', true)
            ->get();

        $classes = [];
        foreach ($sections as $section) {
            // Get class details
            $class = DB::table('classes')->where('id', $section->class_id)->first();

            // Count students in this section
            $studentCount = DB::table('enrollments')
                ->where('section_id', $section->id)
                ->where('status', 'active')
                ->count();

            $activityCount = DB::table('activities')
                ->where('class_id', $class->id)
                ->count();

            // Get next activity (first published activity for this class)
            $nextActivity = DB::table('activities')
                ->where('class_id', $section->class_id)
                //->where('is_published', true)
                ->first();

            $classes[] = [
                'id' => $section->id,
                'name' => $class ? $class->name . ' - Section ' . $section->section_name : 'Section ' . $section->section_name,
                'students' => $studentCount,
                'time' => $section->schedule ?? 'Schedule TBD',
                'nextActivity' => $nextActivity ? $nextActivity->title : 'No activities assigned',
                'teacher' => $teacher->full_name,
                'room' => 'Room ' . $section->id,
                'studentsList' => $this->getStudentsInSection($section->id),
                'activityCount' => $activityCount
            ];
        }

        return response()->json($classes);
    }

    // API: Get today's activities for teacher
    public function getTodayActivities()
    {
        $userId = Auth::id();
        $teacher = DB::table('teachers')->where('user_id', $userId)->first();

        if (!$teacher) {
            return response()->json([]);
        }

        // Get sections for this teacher
        $sections = DB::table('sections')->where('teacher_id', $teacher->id)->get();
        $classIds = DB::table('sections')
            ->where('teacher_id', $teacher->id)
            ->pluck('class_id')
            ->toArray();

        // Get activities for those classes
        $activities = DB::table('activities')
            ->whereIn('class_id', $classIds)
            ->where('is_published', true)
            ->limit(5)
            ->get();

        $todayActivities = [];
        foreach ($activities as $activity) {
            $class = DB::table('classes')->where('id', $activity->class_id)->first();
            $todayActivities[] = [
                'id' => $activity->id,
                'title' => $activity->title,
                'class' => $class ? $class->name : 'Unknown',
                'duration' => $activity->estimated_duration . ' min',
                'activityKey' => $activity->title
            ];
        }

        return response()->json($todayActivities);
    }


    // API: Get all activities for teacher
    public function getAllActivities()
    {
        $userId = Auth::id();
        $teacher = DB::table('teachers')->where('user_id', $userId)->first();

        if (!$teacher) {
            return response()->json([]);
        }

        // Get sections for this teacher
        $classIds = DB::table('sections')
            ->where('teacher_id', $teacher->id)
            ->pluck('class_id')
            ->toArray();

        $activities = DB::table('activities')
            ->whereIn('class_id', $classIds)
            ->where('is_published', true)
            ->orderBy('created_at', 'desc')
            ->get();

        $result = [];
        foreach ($activities as $activity) {
            $class = DB::table('classes')->where('id', $activity->class_id)->first();
            $result[] = [
                'id' => $activity->id,
                'title' => $activity->title,
                'class' => $class ? $class->name : 'Unknown',
                'duration' => $activity->estimated_duration,
                'difficulty' => $activity->difficulty_level,
                'status' => $activity->is_published ? 'Published' : 'Draft'
            ];
        }

        return response()->json($result);
    }

    // API: Get students in a specific section
    private function getStudentsInSection(int $sectionId)
    {
        $students = DB::table('enrollments')
            ->join('students', 'enrollments.student_id', '=', 'students.id')
            ->where('enrollments.section_id', $sectionId)
            ->where('enrollments.status', 'active')
            ->select(['students.id', 'students.full_name'])
            ->get();

        return $students->pluck('full_name')->toArray();
    }

    public function getMyActivities()
{
    $userId = Auth::id();
    $teacher = DB::table('teachers')->where('user_id', $userId)->first();

    if (!$teacher) {
        return response()->json([]);
    }

    // Get class_ids from sections assigned to this teacher
    $classIds = DB::table('sections')
        ->where('teacher_id', $teacher->id)
        ->pluck('class_id')
        ->toArray();

    if (empty($classIds)) {
        return response()->json([]);
    }

    // Get activities for those classes WITH the class name
    $activities = DB::table('activities')
        ->join('classes', 'activities.class_id', '=', 'classes.id')
        ->select(['activities.*', 'classes.name as class_name' ])
        ->whereIn('activities.class_id', $classIds)
        ->where('activities.is_published', true)
        ->orderBy('activities.title', 'asc')
        ->get();

    return response()->json($activities);
}

    // API: Get assessments for teacher's students
    /*public function getAssessments()
    {
        $userId = Auth::id();
        $teacher = DB::table('teachers')->where('user_id', $userId)->first();

        if (!$teacher) {
            return response()->json([]);
        }

        // Get all students in teacher's sections
        $sectionIds = DB::table('sections')
            ->where('teacher_id', $teacher->id)
            ->pluck('id')
            ->toArray();

        $studentIds = DB::table('enrollments')
            ->whereIn('section_id', $sectionIds)
            ->where('status', 'active')
            ->pluck('student_id')
            ->toArray();

        // Get activity completions (assessments)
        $assessments = DB::table('activity_completions')
            ->whereIn('student_id', $studentIds)
            ->orderBy('completion_date', 'desc')
            ->limit(20)
            ->get();

        $result = [];
        foreach ($assessments as $assessment) {
            $student = DB::table('students')->where('id', $assessment->student_id)->first();
            $activity = DB::table('activities')->where('id', $assessment->activity_id)->first();
            $result[] = [
                'id' => $assessment->id,
                'student' => $student ? $student->full_name : 'Unknown',
                'activity' => $activity ? $activity->title : 'Unknown',
                'rating' => $assessment->status === 'completed' ? 4 : 3,
                'comments' => $assessment->feedback ?? '',
                'date' => date('M d, Y', strtotime($assessment->completion_date))
            ];
        }

        return response()->json($result);
    }*/

    // API: Save assessment
    public function storeAssessment(Request $request)
    {
        try {
            $student = DB::table('students')->where('full_name', $request->student)->first();
            $activity = DB::table('activities')->where('title', $request->activity)->first();

            if (!$student || !$activity) {
                return response()->json(['success' => false, 'message' => 'Student or activity not found']);
            }

            DB::table('activity_completions')->insert([
                'student_id' => $student->id,
                'activity_id' => $activity->id,
                'completion_date' => now(),
                'status' => $request->rating >= 3 ? 'completed' : 'in_progress',
                'feedback' => $request->comments,
                'time_spent' => 30,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    // API: Get student list for dropdown
    public function getStudentsList()
    {
        $userId = Auth::id();
        $teacher = DB::table('teachers')->where('user_id', $userId)->first();

        if (!$teacher) {
            return response()->json([]);
        }

        $sectionIds = DB::table('sections')
            ->where('teacher_id', $teacher->id)
            ->pluck('id')
            ->toArray();

        $students = DB::table('enrollments')
            ->join('students', 'enrollments.student_id', '=', 'students.id')
            ->whereIn('enrollments.section_id', $sectionIds)
            ->where('enrollments.status', 'active')
            ->select(['students.id', 'students.full_name'])
            ->get();

        return response()->json($students);
    }

    // API: Get activities list for dropdown
    public function getActivitiesList()
    {
        $userId = Auth::id();
        $teacher = DB::table('teachers')->where('user_id', $userId)->first();

        if (!$teacher) {
            return response()->json([]);
        }

        $classIds = DB::table('sections')
            ->where('teacher_id', $teacher->id)
            ->pluck('class_id')
            ->toArray();

        $activities = DB::table('activities')
            ->whereIn('class_id', $classIds)
            ->where('is_published', true)
            ->select(['id', 'title'])
            ->get();

        return response()->json($activities);
    }

    // API: Change teacher password
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

// API: Get all messages for logged-in teacher
public function getMessages()
{
    $userId = Auth::id();
    $teacher = DB::table('teachers')->where('user_id', $userId)->first();

    if (!$teacher) {
        return response()->json([]);
    }

    // Get messages sent to this teacher
    $received = DB::table('messages')
        ->where('receiver_id', $userId)
        ->where('receiver_type', 'teacher')
        ->whereNull('deleted_at')
        ->orderBy('created_at', 'desc')
        ->get();

    // Get messages sent by this teacher
    $sent = DB::table('messages')
        ->where('sender_id', $userId)
        ->where('sender_type', 'teacher')
        ->whereNull('deleted_at')
        ->orderBy('created_at', 'desc')
        ->get();

    $messages = [];


    foreach ($received as $msg) {
    $sender = $this->getUserInfo($msg->sender_id, $msg->sender_type);
    $messages[] = [
        'id' => $msg->id,
        'from' => $sender['name'],
        'from_id' => $msg->sender_id,  // ADD THIS
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
        'to_id' => $msg->receiver_id,  // ADD THIS
        'to_type' => $msg->receiver_type,
        'subject' => $msg->subject,
        'message' => $msg->message,
        'date' => date('M d, Y H:i', strtotime($msg->created_at)),
        'direction' => 'sent'
    ];
}

    // Sort by date descending
    usort($messages, function($a, $b) {
        return strtotime($b['date']) - strtotime($a['date']);
    });

    return response()->json($messages);
}

// API: Get recipients teacher can message (parents of their students)
public function getRecipients()
{
    $userId = Auth::id();
    $teacher = DB::table('teachers')->where('user_id', $userId)->first();

    if (!$teacher) {
        return response()->json([]);
    }

    // Get sections taught by this teacher
    $sectionIds = DB::table('sections')
        ->where('teacher_id', $teacher->id)
        ->pluck('id');

    // Get students in those sections
    $studentIds = DB::table('enrollments')
        ->whereIn('section_id', $sectionIds)
        ->where('status', 'active')
        ->pluck('student_id');

    // Get parents of those students (unique)
    $parentIds = DB::table('parent_student')
        ->whereIn('student_id', $studentIds)
        ->pluck('parent_id')
        ->unique();

    // Get parent user details
    $recipients = [];
    foreach ($parentIds as $parentId) {
        $parent = DB::table('parents')->where('id', $parentId)->first();
        if ($parent) {
            $user = DB::table('users')->where('id', $parent->user_id)->first();
            if ($user) {
                $recipients[] = [
                    'id' => $user->id,
                    'name' => $parent->full_name,
                    'type' => 'parent',
                    'email' => $user->email
                ];
            }
        }
    }

    // Also add coordinator
    $coordinator = DB::table('users')->where('role_id', 1)->first();
    if ($coordinator) {
        $recipients[] = [
            'id' => $coordinator->id,
            'name' => 'Coordinator',
            'type' => 'coordinator',
            'email' => $coordinator->email
        ];
    }

    return response()->json($recipients);
}

// API: Send a message
public function sendMessage(Request $request)
{
    try {
        $userId = Auth::id();
        $teacher = DB::table('teachers')->where('user_id', $userId)->first();

        if (!$teacher) {
            return response()->json(['success' => false, 'message' => 'Teacher not found']);
        }

        DB::table('messages')->insert([
            'sender_id' => $userId,
            'sender_type' => 'teacher',
            'receiver_id' => $request->receiver_id,
            'receiver_type' => $request->receiver_type,
            'subject' => $request->subject,
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

// Helper: Get user info by ID and type
private function getUserInfo(int $userId, string $type)
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
// API: Get full conversation between teacher and a specific participant (parent or coordinator)
public function getConversation(int $participantId)
{
    $userId = Auth::id();
    $teacher = DB::table('teachers')->where('user_id', $userId)->first();

    if (!$teacher) {
        return response()->json([]);
    }

    // Get the participant's type
    $participant = DB::table('users')->where('id', $participantId)->first();
    $participantType = $participant->role_id == 2 ? 'teacher' : ($participant->role_id == 3 ? 'parent' : 'coordinator');

    // Get all messages between this teacher and the specific participant
    $messages = DB::table('messages')
        ->where(function($query) use ($userId, $participantId, $participantType) {
            $query->where(function($q) use ($userId, $participantId, $participantType) {
                $q->where('sender_id', $userId)
                  ->where('sender_type', 'teacher')
                  ->where('receiver_id', $participantId)
                  ->where('receiver_type', $participantType);
            })->orWhere(function($q) use ($userId, $participantId, $participantType) {
                $q->where('sender_id', $participantId)
                  ->where('sender_type', $participantType)
                  ->where('receiver_id', $userId)
                  ->where('receiver_type', 'teacher');
            });
        })
        ->whereNull('deleted_at')
        ->orderBy('created_at', 'asc')
        ->get();

    $conversation = [];
    foreach ($messages as $msg) {
        if ($msg->sender_type == 'teacher') {
            $sender = $teacher->full_name;
            $sender_type = 'teacher';
        } elseif ($msg->sender_type == 'parent') {
            $parent = DB::table('parents')->where('user_id', $msg->sender_id)->first();
            $sender = $parent ? $parent->full_name : 'Parent';
            $sender_type = 'parent';
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

        // Mark as read if teacher is the receiver
        if ($msg->receiver_id == $userId && !$msg->is_read) {
            DB::table('messages')->where('id', $msg->id)->update(['is_read' => true]);
        }
    }

    return response()->json($conversation);
}

// API: Reply to a message from teacher
public function replyToMessage(Request $request)
{
    try {
        $userId = Auth::id();
        $teacher = DB::table('teachers')->where('user_id', $userId)->first();

        if (!$teacher) {
            return response()->json(['success' => false, 'message' => 'Teacher not found']);
        }

        // Determine receiver type
        $receiver = DB::table('users')->where('id', $request->receiver_id)->first();
        $receiverType = $receiver->role_id == 2 ? 'teacher' : ($receiver->role_id == 3 ? 'parent' : 'coordinator');

        DB::table('messages')->insert([
            'sender_id' => $userId,
            'sender_type' => 'teacher',
            'receiver_id' => $request->receiver_id,
            'receiver_type' => $receiverType,
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

public function getTeacherReport()
{
    $teacherId = Auth::user()->teacher->id;

    $assessments = DB::table('assessments')
        ->join('students', 'assessments.student_id', '=', 'students.id')
        ->join('competencies', 'assessments.competency_id', '=', 'competencies.id')
        ->join('activities', 'competencies.activity_id', '=', 'activities.id')
        ->where('assessments.teacher_id', $teacherId)
        ->select(
            'assessments.rating',
            'assessments.created_at',
            'students.full_name as student_name',
            'competencies.description as competency_name',
            'activities.title as activity_name'
        )
        ->get();

    return response()->json($assessments);
}

public function getSectionActivities($sectionId)
{
    // Find the section
    $section = DB::table('sections')
        ->where('id', $sectionId)
        ->first();
        
    if (!$section) {
        return response()->json([]);
    }
    
    // Get activities for the class this section belongs to
    $activities = DB::table('activities')
        ->where('class_id', $section->class_id)
        ->get();

    return response()->json($activities);
}

public function index()
{
    return view('teacher');
}
}
