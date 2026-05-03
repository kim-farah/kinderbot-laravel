<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Activity;

class ActivityController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'class' => 'required|string',
            'overview' => 'required|string',
            'skills' => 'required|string',
            'materials' => 'required|string',
            'instructions' => 'required|string',
            'duration' => 'required|integer',
            'difficulty' => 'required|string',
            'publish' => 'sometimes|boolean',
        ]);

        // Get class_id from class name
        $class = DB::table('classes')->where('name', $request->class)->first();

        DB::table('activities')->insert([
            'title' => strtoupper($request->title),
            'class_id' => $class->id ?? 1,
            'objective' => $request->overview,
            'materials_needed' => $request->materials,
            'instructions' => $request->instructions,
            'estimated_duration' => $request->duration,
            'difficulty_level' => strtolower($request->difficulty),
            'is_published' => $request->has('publish'),
            'created_by' => session('user_id'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('coordinator')->with('success', 'Activity created successfully!');
    }

    // ADD THIS METHOD
    public function index()
    {
        $activities = DB::table('activities')
            ->join('classes', 'activities.class_id', '=', 'classes.id')
            ->select('activities.*', 'classes.name as class_name')
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

    public function byClass($classId)
    {
        return Activity::where('class_id', $classId)->get();
    }

       public function getSectionActivities($sectionId)
{
    return response()->json(
        DB::table('activities')
            ->where('class_id', $sectionId)
            //->where('is_published', true)
            ->get()
    );
}

public function getActivityData($id)
{
    return response()->json(
        Activity::with(['resources','steps','animations'])->findOrFail($id)
    );
}
}