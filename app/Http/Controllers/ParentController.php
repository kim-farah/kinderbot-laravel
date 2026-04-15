<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ParentController extends Controller
{
    public function dashboard()
    {
        $userId = session('user_id');
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
        $userId = session('user_id');
        $parent = DB::table('parents')->where('user_id', $userId)->first();

        if (!$parent) {
            return response()->json([]);
        }

        $children = DB::table('parent_student')
            ->join('students', 'parent_student.student_id', '=', 'students.id')
            ->where('parent_student.parent_id', $parent->id)
            ->select('students.id', 'students.full_name', 'students.date_of_birth')
            ->get();

        foreach ($children as $child) {
            $age = \Carbon\Carbon::parse($child->date_of_birth)->age;
            $child->age = $age;

            // Get enrollment info
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

    // API: Get child's activities
    public function getChildActivities($childId)
    {
        $completions = DB::table('activity_completions')
            ->where('student_id', $childId)
            ->orderBy('completion_date', 'desc')
            ->get();

        $activities = [];
        foreach ($completions as $completion) {
            $activity = DB::table('activities')->where('id', $completion->activity_id)->first();
            if ($activity) {
                $activities[] = [
                    'id' => $completion->id,
                    'activity' => $activity->title,
                    'date' => date('M d, Y', strtotime($completion->completion_date)),
                    'status' => $completion->status,
                    'rating' => $this->getRatingFromStatus($completion->status)
                ];
            }
        }

        return response()->json($activities);
    }

    // API: Get child's progress
public function getChildProgress($childId)
{
    $totalActivities = DB::table('activities')->count();
    $completedActivities = DB::table('activity_completions')
        ->where('student_id', $childId)
        ->where('status', 'completed')
        ->count();

    $progress = $totalActivities > 0 ? round(($completedActivities / $totalActivities) * 100) : 0;

    // Calculate average rating based on status (completed = 4, in_progress = 2)
    $completions = DB::table('activity_completions')
        ->where('student_id', $childId)
        ->get();

    $totalRating = 0;
    foreach ($completions as $completion) {
        if ($completion->status == 'completed') {
            $totalRating += 4;
        } elseif ($completion->status == 'in_progress') {
            $totalRating += 2;
        }
    }

    $avgRating = $completions->count() > 0 ? $totalRating / $completions->count() : 0;

    // Get notes count
    $notesCount = DB::table('notes')
        ->where('student_id', $childId)
        ->count();

    return response()->json([
        'progress' => $progress,
        'completedCount' => $completedActivities,
        'avgRating' => round($avgRating, 1),
        'notesCount' => $notesCount
    ]);
}
    // API: Get notes for child
    public function getChildNotes($childId)
    {
        $notes = DB::table('notes')
            ->where('student_id', $childId)
            ->orderBy('created_at', 'desc')
            ->get();

        $result = [];
        foreach ($notes as $note) {
            $teacher = DB::table('teachers')->where('id', $note->created_by)->first();
            $result[] = [
                'id' => $note->id,
                'teacher' => $teacher ? $teacher->full_name : 'Teacher',
                'date' => date('M d, Y', strtotime($note->created_at)),
                'message' => $note->content
            ];
        }

        return response()->json($result);
    }

    // API: Get messages for parent
    public function getMessages()
    {
        $userId = session('user_id');
        $parent = DB::table('parents')->where('user_id', $userId)->first();

        if (!$parent) {
            return response()->json([]);
        }

        // Get messages sent to this parent
        $messages = DB::table('messages')
            ->where('parent_id', $parent->id)
            ->orWhere('parent_id', null)
            ->orderBy('created_at', 'desc')
            ->get();

        $result = [];
        foreach ($messages as $message) {
            $sender = $message->sender_type === 'teacher'
                ? DB::table('teachers')->where('id', $message->sender_id)->first()
                : DB::table('users')->where('id', $message->sender_id)->first();

            $result[] = [
                'id' => $message->id,
                'from' => $sender ? $sender->full_name ?? $sender->email : 'System',
                'message' => $message->content,
                'date' => date('M d, Y', strtotime($message->created_at))
            ];
        }

        return response()->json($result);
    }

    // API: Send message
    public function sendMessage(Request $request)
    {
        try {
            $userId = session('user_id');
            $parent = DB::table('parents')->where('user_id', $userId)->first();

            DB::table('messages')->insert([
                'parent_id' => $parent->id,
                'sender_id' => $userId,
                'sender_type' => 'parent',
                'content' => $request->message,
                'subject' => $request->subject,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    // Helper methods
    private function getTeacherName($teacherId)
    {
        if (!$teacherId) return 'Not assigned';
        $teacher = DB::table('teachers')->where('id', $teacherId)->first();
        return $teacher ? $teacher->full_name : 'Not assigned';
    }

    private function getRatingFromStatus($status)
    {
        switch ($status) {
            case 'completed': return 4;
            case 'in_progress': return 2;
            default: return 3;
        }
    }
}
