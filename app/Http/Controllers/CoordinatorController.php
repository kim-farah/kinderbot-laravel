<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CoordinatorController extends Controller
{
    public function dashboard()
    {
        $classes = DB::table('classes')->get();
        $activities = DB::table('activities')->orderBy('created_at', 'desc')->take(5)->get();
        $teacherLog = DB::table('teacher_activity_log')
            ->orderBy('timestamp', 'desc')
            ->take(10)
            ->get();

        return view('coordinator', [
            'classes' => $classes,
            'activities' => $activities,
            'teacherLog' => $teacherLog,
        ]);
    }

    public function getClasses()
    {
        $classes = DB::table('classes')->get();
        return response()->json($classes);
    }

    public function getTeachers()
    {
        $teachers = DB::table('teachers')->get();
        return response()->json($teachers);
    }

    public function getParents()
    {
        $parents = DB::table('parents')->get();
        return response()->json($parents);
    }

    public function getSections($classId)
{
    $sections = DB::table('sections')->where('class_id', $classId)->get();
    foreach ($sections as $section) {
        $section->students = DB::table('enrollments')
            ->join('students', 'enrollments.student_id', '=', 'students.id')
            ->where('enrollments.section_id', $section->id)
            ->select('students.id', 'students.full_name')
            ->get();
    }
    return response()->json($sections);
}

    public function getAllActivities()
    {
        $activities = DB::table('activities')->orderBy('created_at', 'desc')->get();
        return response()->json($activities);
    }

    public function deleteClass($id)
    {
        try {
            DB::table('classes')->where('id', $id)->delete();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function updateClass(Request $request, $id)
    {
        try {
            DB::table('classes')->where('id', $id)->update([
                'name' => $request->name,
                'grade_level' => $request->grade_level,
                'updated_at' => now()
            ]);
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function deleteActivity($id)
    {
        try {
            DB::table('activities')->where('id', $id)->delete();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function deleteTeacher($id)
    {
        try {
            DB::table('teachers')->where('id', $id)->delete();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function updateTeacher(Request $request, $id)
    {
        try {
            DB::table('teachers')->where('id', $id)->update([
                'full_name' => $request->full_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'updated_at' => now()
            ]);
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function deleteParent($id)
    {
        try {
            DB::table('parents')->where('id', $id)->delete();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function updateParent(Request $request, $id)
    {
        try {
            DB::table('parents')->where('id', $id)->update([
                'full_name' => $request->full_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'updated_at' => now()
            ]);
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function getSectionsWithDetails($classId)
    {
        $sections = DB::table('sections')
            ->where('class_id', $classId)
            ->get();

        foreach ($sections as $section) {
            $teacher = DB::table('teachers')->where('id', $section->teacher_id)->first();
            $section->teacher_name = $teacher ? $teacher->full_name : 'Not assigned';

            $section->students = DB::table('enrollments')
                ->join('students', 'enrollments.student_id', '=', 'students.id')
                ->where('enrollments.section_id', $section->id)
                ->select('students.id', 'students.full_name')
                ->get();
        }

        return response()->json($sections);
    }

    public function addSection(Request $request)
    {
        try {
            $sectionId = DB::table('sections')->insertGetId([
                'class_id' => $request->class_id,
                'section_name' => $request->section_name,
                'teacher_id' => $request->teacher_id ?? null,
                'max_students' => $request->max_students ?? 25,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return response()->json(['success' => true, 'id' => $sectionId]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function updateSection(Request $request, $id)
    {
        try {
            $updateData = [
                'section_name' => $request->section_name,
                'max_students' => $request->max_students,
                'updated_at' => now(),
            ];

            if ($request->has('teacher_id')) {
                $updateData['teacher_id'] = $request->teacher_id;
            }

            DB::table('sections')->where('id', $id)->update($updateData);

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function deleteSection($id)
    {
        try {
            DB::table('enrollments')->where('section_id', $id)->delete();
            DB::table('sections')->where('id', $id)->delete();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function addStudentToSection(Request $request)
    {
        try {
            $userId = DB::table('users')->insertGetId([
                'email' => strtolower(str_replace(' ', '.', $request->student_name)) . '@student.kinderbot.com',
                'password' => bcrypt('password123'),
                'role_id' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $studentId = DB::table('students')->insertGetId([
                'full_name' => $request->student_name,
                'user_id' => $userId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('enrollments')->insert([
                'student_id' => $studentId,
                'section_id' => $request->section_id,
                'enrollment_date' => now(),
                'status' => 'active',
                'created_at' => now(),
            ]);

            if ($request->parent_name) {
                $parentUserId = DB::table('users')->insertGetId([
                    'email' => strtolower(str_replace(' ', '.', $request->parent_name)) . '@parent.com',
                    'password' => bcrypt('password123'),
                    'role_id' => 3,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                DB::table('parents')->insert([
                    'full_name' => $request->parent_name,
                    'user_id' => $parentUserId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function removeStudentFromSection($sectionId, $studentId)
    {
        try {
            DB::table('enrollments')
                ->where('section_id', $sectionId)
                ->where('student_id', $studentId)
                ->delete();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function getTeachersList()
    {
        $teachers = DB::table('teachers')->select('id', 'full_name')->get();
        return response()->json($teachers);
    }
}
