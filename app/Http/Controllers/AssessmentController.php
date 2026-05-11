<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Activity;

class AssessmentController extends Controller
{
    private function getStudentsInSection($sectionId)
    {
        return DB::table('enrollments')
            ->join('students', 'enrollments.student_id', '=', 'students.id')
            ->where('enrollments.section_id', $sectionId)
            ->where('enrollments.status', 'active')
            ->select('students.id', 'students.full_name')
            ->get();
    }

    // =========================
    // GET DATA FOR FRONTEND
    // =========================
    public function getData($activityId, $sectionId)
    {
        $students = DB::table('enrollments')
            ->join('students', 'enrollments.student_id', '=', 'students.id')
            ->where('enrollments.section_id', $sectionId)
            ->where('enrollments.status', 'active')
            ->select('students.id', 'students.full_name')
            ->get();

        $competencies = DB::table('competencies')
            ->where('activity_id', $activityId)
            ->select('id', 'description')
            ->get();

        return response()->json([
            'students' => $students,
            'competencies' => $competencies
        ]);
    }

    // =========================
    // SUBMIT ASSESSMENT
    // =========================
    public function submit(Request $request)
    {
        $teacherId = DB::table('teachers')
        ->where('user_id', auth()->id())
        ->value('id');
        $ratings = $request->ratings;
        $completions = $request->completions;

        // RATINGS
        foreach ($ratings as $studentId => $comps) {
            foreach ($comps as $competencyId => $rating) {

                DB::table('assessments')->updateOrInsert(
                    [
                        'student_id' => $studentId,
                        'competency_id' => $competencyId,
                    ],
                    [
                        'teacher_id' => $teacherId,
                        'rating' => $rating,
                        'comment' => $data['comment'] ?? null,
                        'updated_at' => now(),
                        'created_at' => now(),
                    ]
                );
            }
        }

        // COMPLETION + COMMENT
        foreach ($completions as $studentId => $data) {

            DB::table('activity_completions')->updateOrInsert(
                [
                    'student_id' => $studentId,
                    'activity_id' => $data['activity_id'],
                ],
                [
                    'activity_completion_status_id' => $data['completed'] ? 2 : 1,
                    'completion_date' => now(),
                   
                ]
            );
        }

        return response()->json([
            'message' => 'Assessment saved successfully'
        ]);
    }

    // OPTIONAL (Blade view if needed)
    public function show($activityId, $sectionId)
    {
        $activity = Activity::with(['competencies'])->findOrFail($activityId);

        $students = $this->getStudentsInSection($sectionId);

        return view('assessments.show', compact('activity', 'students', 'sectionId'));
    }
}
