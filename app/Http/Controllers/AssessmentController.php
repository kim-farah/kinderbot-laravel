<?php

namespace App\Http\Controllers;

use App\Models\Activity;

class AssessmentController extends Controller
{
    public function show($id)
    {
        $activity = Activity::with([
            'competencies',
            'class.sections.students.user'
        ])->findOrFail($id);

        $students = $activity->class
                    ->sections
                    ->first()
                    ->students;

        return view(
            'assessments.show',
            compact('activity','students')
        );
    }
}