<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
}
