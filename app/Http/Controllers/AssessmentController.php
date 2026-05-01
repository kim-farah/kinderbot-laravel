<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AssessmentController extends Controller
{
    private function getStudentsInSection(int $sectionId)
    {
        return DB::table('enrollments')
            ->join('students', 'enrollments.student_id', '=', 'students.id')
            ->where('enrollments.section_id', $sectionId)
            ->where('enrollments.status', 'active')
            ->select(['students.id', 'students.full_name'])
            ->get();
    }

public function submit(Request $request)
{
    $activityId = $request->activity_id;

    /* SAVE COMPETENCY SCORES */
    foreach($request->assessments as $a){

        DB::table('assessments')->updateOrInsert(
            [
                'student_id' => $a['student_id'],
                'activity_id' => $activityId,
                'competency_id' => $a['competency_id'],
            ],
            [
                'score' => $a['rating']
            ]
        );

    }

    /* SAVE COMPLETION */
    foreach($request->completions as $c){

        DB::table('activity_completions')->updateOrInsert(
            [
                'student_id' => $c['student_id'],
                'activity_id' => $activityId,
            ],
            [
                'completed' => $c['completed']
            ]
        );

    }

    return response()->json(['status' => 'success']);
}

public function getData(int $id)
{
    // Get students (for teacher's classes)
    $students = DB::table('students')->get();

    // Get competencies for this activity
    $competencies = DB::table('competencies')->where('activity_id', $id)->get();

    return response()->json([
        'students' => $students,
        'competencies' => $competencies
    ]);
}

public function index(int $id)
{
    $activity = DB::table('activities')->where('id', $id)->first();

    if (!$activity) {
        abort(404);
    }

    return view('assessments.show', ['id' => $id, 'activity' => $activity]);
}
}
