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
                $filename = time() . '_' . $index . '_' . $file->getClientOriginalName();
                $file->storeAs('public/activities', $filename);

                DB::table('resources')->insert([
                    'activity_id' => $activityId,
                    'title' => $resourceTitles[$index] ?? 'Resource ' . ($index + 1),
                    'file_path' => 'activities/' . $filename,
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
            $filename = time() . '_step_' . $index . '_' . $stepImages[$index]->getClientOriginalName();
            $stepImages[$index]->storeAs('public/activities', $filename);
            $imagePath = 'activities/' . $filename;
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

    /*
    public function show($id)
    {
        return Activity::with([
            'resources',
            'steps',
            'animations'
        ])->findOrFail($id);
    }*/

public function show(int $id)
{
    $activity = DB::table('activities')->where('id', $id)->first();


    if (!$activity) {
        abort(404);
    }

    // Get resources (images)
    $resources = DB::table('resources')->where('activity_id', $id)->get();

    // Get steps
    $steps = DB::table('activity_steps')->where('activity_id', $id)->orderBy('order')->get();

    // Get competencies (skills)
    $competencies = DB::table('competencies')->where('activity_id', $id)->get();

    return view('activities.show', compact('activity', 'resources', 'steps', 'competencies'));
}

//new
public function getActivityData(int $id)
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
// Add this method for edit page
public function edit(int $id)
{
    $activity = DB::table('activities')->where('id', $id)->first();
    $classes = DB::table('classes')->get();
    $resources = DB::table('resources')->where('activity_id', $id)->get();
    $steps = DB::table('activity_steps')->where('activity_id', $id)->orderBy('order')->get();

    return view('coordinator-create', [
        'activity' => $activity,
        'classes' => $classes,
        'resources' => $resources,
        'steps' => $steps,
        'is_edit' => true
    ]);
}


public function update(Request $request, int $id)
{
    $request->validate([
        'title' => 'required|string',
        'class' => 'required|string',
        'objective' => 'required|string',
        'overview' => 'required|string',
        'skills' => 'required|string',
        'materials' => 'required|string',
        'step_description' => 'required|array',
        'step_description.*' => 'required|string',
    ]);

    // Get class_id
    $class = DB::table('classes')->where('name', $request->class)->first();
    if (!$class) {
        return back()->withErrors(['class' => 'Class not found']);
    }

    // Update activity - ONLY updated_at changes
    DB::table('activities')->where('id', $id)->update([
        'title' => $request->title,
        'class_id' => $class->id,
        'objective' => $request->objective,
        'overview' => $request->overview,
        'skills_competencies' => $request->skills,
        'materials' => $request->materials,
        'rodin_comment' => $request->rodin_comment,
        'activity_comment' => $request->activity_comment,
        'feedback_comment' => $request->feedback_comment,
        'is_published' => $request->has('is_published'),
        'updated_at' => now(),
    ]);


    // ========== UPDATE RESOURCES ==========
$resourceTitles = ['Hero Image', 'Switch Image 1', 'Switch Image 2'];

// First, update the updated_at timestamp for ALL existing resources when activity is updated
DB::table('resources')
    ->where('activity_id', $id)
    ->update(['updated_at' => now()]);

// Then handle new file uploads
if ($request->hasFile('resources')) {
    foreach ($request->file('resources') as $index => $file) {
        if ($file && $file->isValid()) {
            $title = $resourceTitles[$index] ?? 'Resource ' . ($index + 1);

            $existingResource = DB::table('resources')
                ->where('activity_id', $id)
                ->where('title', $title)
                ->first();

            // Delete old file if exists
            if ($existingResource && $existingResource->file_path) {
                $oldPath = storage_path('app/public/' . $existingResource->file_path);
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }

            // Save new file
            $filename = time() . '_' . $index . '_' . $file->getClientOriginalName();
            $file->storeAs('public/activities', $filename);

            if ($existingResource) {
                DB::table('resources')
                    ->where('id', $existingResource->id)
                    ->update([
                        'file_path' => 'activities/' . $filename,
                        'updated_at' => now(),
                    ]);
            } else {
                DB::table('resources')->insert([
                    'activity_id' => $id,
                    'title' => $title,
                    'file_path' => 'activities/' . $filename,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}

    // ========== UPDATE STEPS - DON'T DELETE, UPDATE EXISTING ==========
    $stepDescriptions = $request->step_description;
    $stepImages = $request->file('step_images') ?? [];

    // Get existing steps
    $existingSteps = DB::table('activity_steps')
        ->where('activity_id', $id)
        ->orderBy('order')
        ->get();

    // Update or create steps
    foreach ($stepDescriptions as $index => $description) {
        if (!empty(trim($description))) {
            $imagePath = null;

            // Check if this step already has an image
            if (isset($existingSteps[$index]) && $existingSteps[$index]->image_path) {
                $imagePath = $existingSteps[$index]->image_path;
            }

            // If new image uploaded, replace it
            if (isset($stepImages[$index]) && $stepImages[$index]->isValid()) {
                // Delete old image file if exists
                if ($imagePath) {
                    $oldPath = storage_path('app/public/' . $imagePath);
                    if (file_exists($oldPath)) {
                        unlink($oldPath);
                    }
                }
                $filename = time() . '_step_' . $index . '_' . $stepImages[$index]->getClientOriginalName();
                $stepImages[$index]->storeAs('public/activities', $filename);
                $imagePath = 'activities/' . $filename;
            }

            if (isset($existingSteps[$index])) {
                // Update existing step
                DB::table('activity_steps')
                    ->where('id', $existingSteps[$index]->id)
                    ->update([
                        'description' => $description,
                        'image_path' => $imagePath,
                        'order' => $index + 1,
                        'updated_at' => now(),
                    ]);
            } else {
                // Insert new step
                DB::table('activity_steps')->insert([
                    'activity_id' => $id,
                    'description' => $description,
                    'image_path' => $imagePath,
                    'order' => $index + 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    // Remove steps that were deleted
    if (count($stepDescriptions) < count($existingSteps)) {
        for ($i = count($stepDescriptions); $i < count($existingSteps); $i++) {
            // Delete image file
            if ($existingSteps[$i]->image_path) {
                $oldPath = storage_path('app/public/' . $existingSteps[$i]->image_path);
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }
            DB::table('activity_steps')->where('id', $existingSteps[$i]->id)->delete();
        }
    }

    // ========== UPDATE COMPETENCIES ==========
    // Delete old and insert new (competencies are completely replaced)
    DB::table('competencies')->where('activity_id', $id)->delete();
    $skillNames = explode("\n", $request->skills);
    foreach ($skillNames as $index => $skillName) {
        $skillName = trim($skillName);
        if (!empty($skillName)) {
            DB::table('competencies')->insert([
                'activity_id' => $id,
                'name' => 'skill_' . ($index + 1),
                'description' => $skillName,
            ]);
        }
    }

    return redirect()->route('coordinator')->with('success', 'Activity updated successfully!');
}
}
