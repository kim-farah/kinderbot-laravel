<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Activity;

class ActivityController extends Controller
{

public function store(Request $request)
{

    $request->validate([
        'title' => 'required|string',
        'class' => 'required|string',
        'objective' => 'required|string',
        'overview' => 'required|string',
        'skills' => 'required|string',
        'materials' => 'required|string',
        //'instructions' => 'required|string',
        'step_description' => 'required|array',
        'step_description.*' => 'required|string',
    ]);

    // Get class_id
    $class = DB::table('classes')->where('name', $request->class)->first();
    if (!$class) {
        return back()->withErrors(['class' => 'Class not found']);
    }

    // Insert into activities table
    $activityId = DB::table('activities')->insertGetId([
        'title' => $request->title,
        'class_id' => $class->id,
        'objective' => $request->objective,
        'overview' => $request->overview,
        'skills_competencies' => $request->skills,
        'materials' => $request->materials,
        //'description' => $request->instructions,
        'rodin_comment' => $request->rodin_comment,
        'activity_comment' => $request->activity_comment,
        'feedback_comment' => $request->feedback_comment,
        'is_published' => $request->has('is_published'),
        'created_at' => now(),
        //'updated_at' => now(),
    ]);

    // Save Resources (Images)
    $resourceTitles = [
        'Hero Image','Switch Image 1', 'Switch Image 2'];

    if ($request->hasFile('resources')) {
        foreach ($request->file('resources') as $index => $file) {
            if ($file && $file->isValid()) {
                $filename = $file->getClientOriginalName();
                $file->storeAs('public/resources', $filename);

                DB::table('resources')->insert([
                    'activity_id' => $activityId,
                    'title' => $resourceTitles[$index] ?? 'Resource ' . ($index + 1),
                    'file_path' => 'resources/' . $filename,
                    'created_at' => now(),
                    //'updated_at' => now(),
                ]);
            }
        }
    }

    // Save Steps
    $stepDescriptions = $request->step_description;
    $stepImages = $request->file('step_images') ?? [];

    foreach ($stepDescriptions as $index => $description) {
        if (!empty(trim($description))) {
        $imagePath = null;

        if (isset($stepImages[$index]) && $stepImages[$index]->isValid()) {
            $filename = $stepImages[$index]->getClientOriginalName();
            $stepImages[$index]->storeAs('public/resources', $filename);
            $imagePath = 'resources/' . $filename;
        }

        DB::table('activity_steps')->insert([
            'activity_id' => $activityId,
            'description' => $description,
            'image_path' => $imagePath,
            'order' => $index + 1,
            'created_at' => now(),
            //'updated_at' => now(),
        ]);
    }}

// Save Competencies - FIX HERE
    $skillNames = explode("\n", $request->skills);
    foreach ($skillNames as $index => $skillName) {
        $skillName = trim($skillName);
        if (!empty($skillName)) {
            DB::table('competencies')->insert([
                'activity_id' => $activityId,
                'name' => 'skill_' . ($index + 1),
                'description' => ($skillName)
            ]);
        }
    }

    return redirect()->route('coordinator')->with('success', 'Activity created successfully!');

}
    // ADD THIS METHOD
    public function index()
    {
        $activities = DB::table('activities')
            ->join('classes', 'activities.class_id', '=', 'classes.id')
            ->select('activities.*')
            ->addSelect('classes.name as class_name')
            ->orderBy('activities.created_at', 'desc')
            ->get();

        return response()->json($activities);
    }

   public function show($id)
        {
            $activity = Activity::with([
                'resources',
                'steps',
                'animations'
            ])->findOrFail($id);

            return view('activities.show', compact('activity'));
        }

    // Get resources (images)
 /*   $resources = DB::table('resources')->where('activity_id', $id)->get();

    // Get steps
    $steps = DB::table('activity_steps')->where('activity_id', $id)->orderBy('order')->get();

    // Get competencies (skills)
    $competencies = DB::table('competencies')->where('activity_id', $id)->get();

    return view('activities.show', compact('activity', 'resources', 'steps', 'competencies'));
}
*/




//new
public function getActivityData1(int $id)
{
    $activity = DB::table('activities')->where('id', $id)->first();

    if (!$activity) {
        return response()->json(['error' => 'Activity not found'], 404);
    }

    $resources = DB::table('resources')->where('activity_id', $id)->get();
    $steps = DB::table('activity_steps')->where('activity_id', $id)->orderBy('order')->get();

    return response()->json([
        'id' => $activity->id,
        'title' => $activity->title,
        'objective' => $activity->objective,
        'overview' => $activity->overview,
        'skills_competencies' => $activity->skills_competencies,
        'materials' => $activity->materials,
        'rodin_comment' => $activity->rodin_comment,
        'activity_comment' => $activity->activity_comment,
        'feedback_comment' => $activity->feedback_comment,
        'resources' => $resources,
        'steps' => $steps
    ]);
}
    public function byClass(int $classId)
    {
        return Activity::where('class_id', $classId)->get();
    }

 /*      public function getSectionActivities($sectionId)
{
    return response()->json(
        DB::table('activities')
            ->where('class_id', $sectionId)
            //->where('is_published', true)
            ->get()
    );
}*/

public function getActivityData($id)
{
    return response()->json(
        Activity::with(['resources','steps','animations'])->findOrFail($id)
    );
}

public function getSectionActivities1($sectionId)
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
}