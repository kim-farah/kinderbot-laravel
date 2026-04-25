<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClassController extends Controller
{
    public function store(Request $request)
    {
        try {
            DB::table('classes')->insert([
                'name' => $request->name,
                'grade_level' => $request->grade_level,
                'program_id' => 1,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    // ADD THESE METHODS
    public function index()
    {
        $classes = DB::table('classes')->get();
        return response()->json($classes);
    }

    public function show($id)
    {
        $class = DB::table('classes')->where('id', $id)->first();
        return response()->json($class);
    }

    public function update(Request $request, $id)
    {
        try {
            DB::table('classes')->where('id', $id)->update([
                'name' => $request->name,
                'grade_level' => $request->grade_level,
                'updated_at' => now(),
            ]);
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        try {
            DB::table('classes')->where('id', $id)->delete();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
