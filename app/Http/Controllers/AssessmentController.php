<?php

namespace App\Http\Controllers;

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

public function submit(Request $request)
{
    $activityId = $request->activity_id;

    /* SAVE COMPETENCY SCORES */
    foreach($request->assessments as $a){

        DB::table('assessment')->updateOrInsert(
            [
                'student_id' => $a['student_id'],
                'activity_id' => $activityId,
                'competency_id' => $a['competency_id'],
            ],
            [
                'score' => $a['score']
            ]
        );

    }

    /* SAVE COMPLETION */
    foreach($request->completions as $c){

        DB::table('activity_completion')->updateOrInsert(
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
}