<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TeacherController extends Controller
{
    public function dashboard()
    {
        // Get the logged-in teacher's user_id from session
        $userId = session('user_id');

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
        $userId = session('user_id');
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

            // Get next activity (first published activity for this class)
            $nextActivity = DB::table('activities')
                ->where('class_id', $section->class_id)
                ->where('is_published', true)
                ->first();

            $classes[] = [
                'id' => $section->id,
                'name' => $class ? $class->name . ' - Section ' . $section->section_name : 'Section ' . $section->section_name,
                'students' => $studentCount,
                'time' => $section->schedule ?? 'Schedule TBD',
                'nextActivity' => $nextActivity ? $nextActivity->title : 'No activities assigned',
                'teacher' => $teacher->full_name,
                'room' => 'Room ' . $section->id,
                'studentsList' => $this->getStudentsInSection($section->id)
            ];
        }

        return response()->json($classes);
    }

    // API: Get today's activities for teacher
    public function getTodayActivities()
    {
        $userId = session('user_id');
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
        $userId = session('user_id');
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
    private function getStudentsInSection($sectionId)
    {
        $students = DB::table('enrollments')
            ->join('students', 'enrollments.student_id', '=', 'students.id')
            ->where('enrollments.section_id', $sectionId)
            ->where('enrollments.status', 'active')
            ->select('students.id', 'students.full_name')
            ->get();

        return $students->pluck('full_name')->toArray();
    }

    // API: Get assessments for teacher's students
    public function getAssessments()
    {
        $userId = session('user_id');
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
    }

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
        $userId = session('user_id');
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
            ->select('students.id', 'students.full_name')
            ->get();

        return response()->json($students);
    }

    // API: Get activities list for dropdown
    public function getActivitiesList()
    {
        $userId = session('user_id');
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
            ->select('id', 'title')
            ->get();

        return response()->json($activities);
    }
}
