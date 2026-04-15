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

    // ==================== HELPER METHODS ====================
/**
 * Generate a random secure password
 */
private function generateRandomPassword($length = 10)
{
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%';
    return substr(str_shuffle($chars), 0, $length);
}
    /**
     * Calculate current age based on date of birth.
     */
    private function calculateCurrentAge($dateOfBirth)
    {
        if (!$dateOfBirth) return null;
        $dob = new \DateTime($dateOfBirth);
        $now = new \DateTime();
        return $now->diff($dob)->y;
    }

    /**
     * Get the suggested class for a student based on their current age.
     * It uses the `age_range` defined in the classes table (e.g., "Ages 3-4").
     */
    private function getSuggestedClassByAge($age)
    {
        $classes = DB::table('classes')->get();
        foreach ($classes as $class) {
            if ($class->age_range && preg_match('/(\d+)-(\d+)/', $class->age_range, $matches)) {
                $minAge = (int)$matches[1];
                $maxAge = (int)$matches[2];
                if ($age >= $minAge && $age <= $maxAge) {
                    return $class;
                }
            }
        }
        return null;
    }

    // ==================== STUDENT METHODS ====================

    /**
     * Get all students for the Students page.
     */
    public function getStudents()
    {
        $students = DB::table('students')
            ->leftJoin('parent_student', 'students.id', '=', 'parent_student.student_id')
            ->leftJoin('parents', 'parent_student.parent_id', '=', 'parents.id')
            ->select('students.*', 'parents.full_name as parent_name')
            ->get();

        foreach ($students as $student) {
            $student->current_age = $this->calculateCurrentAge($student->date_of_birth);
            $suggestedClass = $this->getSuggestedClassByAge($student->current_age);
            $student->suggested_class_name = $suggestedClass ? $suggestedClass->name : 'Not applicable';
            $student->suggested_age_range = $suggestedClass ? $suggestedClass->age_range : '';
            $student->is_enrolled = DB::table('enrollments')->where('student_id', $student->id)->exists();
        }
        return response()->json($students);
    }

    /**
     * Get a single student for editing.
     */
    public function getStudent($id)
    {
        $student = DB::table('students')->where('id', $id)->first();
        $parentLink = DB::table('parent_student')->where('student_id', $id)->first();
        if ($parentLink) {
            $parent = DB::table('parents')->where('id', $parentLink->parent_id)->first();
            $student->parent_id = $parentLink->parent_id;
            $student->parent_name = $parent ? $parent->full_name : null;
        }
        return response()->json($student);
    }

    /**
     * Update a student's information.
     */
    public function updateStudent(Request $request, $id)
    {
        try {
            DB::table('students')->where('id', $id)->update([
                'full_name' => $request->full_name,
                'date_of_birth' => $request->date_of_birth,
                'updated_at' => now()
            ]);
            if ($request->parent_id) {
                DB::table('parent_student')->where('student_id', $id)->delete();
                DB::table('parent_student')->insert([
                    'parent_id' => $request->parent_id,
                    'student_id' => $id,
                ]);
            }
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Store a new student.
     */
    public function storeStudent(Request $request)
    {
        try {
            $userId = DB::table('users')->insertGetId([
                'email' => strtolower(str_replace(' ', '.', $request->full_name)) . '@student.kinderbot.com',
                'password' => bcrypt('password123'),
                'role_id' => 3,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $studentId = DB::table('students')->insertGetId([
                'user_id' => $userId,
                'full_name' => $request->full_name,
                'date_of_birth' => $request->date_of_birth,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            if ($request->parent_id) {
                DB::table('parent_student')->insert([
                    'parent_id' => $request->parent_id,
                    'student_id' => $studentId,
                ]);
            }
            return response()->json(['success' => true, 'student_id' => $studentId]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Delete a student and all related records.
     */
    public function deleteStudent($id)
    {
        try {
            DB::table('enrollments')->where('student_id', $id)->delete();
            DB::table('parent_student')->where('student_id', $id)->delete();
            $student = DB::table('students')->where('id', $id)->first();
            if ($student) {
                DB::table('users')->where('id', $student->user_id)->delete();
            }
            DB::table('students')->where('id', $id)->delete();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Get students who are available to be added to a specific section.
     * Rules:
     * 1. Student's age must match the class's age_range.
     * 2. Student must NOT be enrolled in ANY class.
     */
    public function getAvailableStudentsForSection($sectionId)
    {
        $section = DB::table('sections')->where('id', $sectionId)->first();
        if (!$section) {
            return response()->json([]);
        }

        $class = DB::table('classes')->where('id', $section->class_id)->first();
        $minAge = $maxAge = null;
        if ($class->age_range && preg_match('/(\d+)-(\d+)/', $class->age_range, $matches)) {
            $minAge = (int)$matches[1];
            $maxAge = (int)$matches[2];
        }

        $availableStudents = [];
        $allStudents = DB::table('students')->get();
        foreach ($allStudents as $student) {
            $age = $this->calculateCurrentAge($student->date_of_birth);
            $isEnrolled = DB::table('enrollments')->where('student_id', $student->id)->exists();
            if (!$isEnrolled && $age >= $minAge && $age <= $maxAge) {
                $student->current_age = $age;
                $availableStudents[] = $student;
            }
        }
        return response()->json($availableStudents);
    }

    /**
     * Add a student to a section (enroll them).
     * Final check to ensure the student is not already enrolled.
     */
    public function addStudentToSection(Request $request)
    {
        try {
            $isEnrolled = DB::table('enrollments')->where('student_id', $request->student_id)->exists();
            if ($isEnrolled) {
                return response()->json(['success' => false, 'message' => 'Student is already enrolled in a class.']);
            }
            DB::table('enrollments')->insert([
                'student_id' => $request->student_id,
                'section_id' => $request->section_id,
                'enrollment_date' => now(),
                'status' => 'active',
                'created_at' => now(),
            ]);
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Remove a student from a section.
     */
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

    /**
     * Get a simple list of students for dropdowns.
     */
    public function getStudentsList()
    {
        $students = DB::table('students')->select('id', 'full_name')->orderBy('full_name')->get();
        return response()->json($students);
    }

    // ==================== CLASS METHODS ====================

    public function getClass($id)
    {
        $class = DB::table('classes')->where('id', $id)->first();
        return response()->json($class);
    }

    public function getClasses()
    {
        $classes = DB::table('classes')->get();
        return response()->json($classes);
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
                'age_range' => $request->age_range,
                'updated_at' => now()
            ]);
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function getAllClassesWithDetails()
    {
        $classes = DB::table('classes')->get();
        foreach ($classes as $class) {
            $sections = DB::table('sections')->where('class_id', $class->id)->get();
            $totalStudents = 0;
            $sectionsList = [];
            foreach ($sections as $section) {
                $sectionsList[] = $section->section_name;
                $totalStudents += DB::table('enrollments')->where('section_id', $section->id)->count();
            }
            $class->totalStudents = $totalStudents;
            $class->sectionsList = implode(', ', $sectionsList);
        }
        return response()->json($classes);
    }

    // ==================== TEACHER METHODS ====================

    public function getTeachers()
    {
        $teachers = DB::table('teachers')->select('id', 'full_name', 'email', 'phone')->get();
        return response()->json($teachers);
    }

    public function deleteTeacher($id)
    {
        try {
            $teacher = DB::table('teachers')->where('id', $id)->first();
            if ($teacher) {
                DB::table('users')->where('id', $teacher->user_id)->delete();
            }
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

    public function storeTeacher(Request $request)
{
    try {
        $plainPassword = $this->generateRandomPassword();

        $userId = DB::table('users')->insertGetId([
            'email' => $request->email ?: strtolower(str_replace(' ', '.', $request->full_name)) . '@teacher.kinderbot.com',
            'password' => bcrypt($plainPassword),
            'role_id' => 2,  // Teacher role
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('teachers')->insert([
            'user_id' => $userId,
            'full_name' => $request->full_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Return the password so coordinator can see it
        return response()->json([
            'success' => true,
            'password' => $plainPassword,
            'email' => $request->email ?: strtolower(str_replace(' ', '.', $request->full_name)) . '@teacher.kinderbot.com'
        ]);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => $e->getMessage()]);
    }
}

    public function getTeachersList()
    {
        $teachers = DB::table('teachers')->select('id', 'full_name')->get();
        return response()->json($teachers);
    }

    // ==================== PARENT METHODS ====================

    public function getParents()
    {
        $parents = DB::table('parents')->select('id', 'full_name', 'email', 'phone')->get();
        return response()->json($parents);
    }

    public function deleteParent($id)
    {
        try {
            $parent = DB::table('parents')->where('id', $id)->first();
            if ($parent) {
                DB::table('users')->where('id', $parent->user_id)->delete();
            }
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

    public function storeParent(Request $request)
{
    try {
        if (!$request->email) {
            return response()->json(['success' => false, 'message' => 'Email is required']);
        }

        $plainPassword = $this->generateRandomPassword();

        $userId = DB::table('users')->insertGetId([
            'email' => $request->email,
            'password' => bcrypt($plainPassword),
            'role_id' => 3,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('parents')->insert([
            'user_id' => $userId,
            'full_name' => $request->full_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'password' => $plainPassword,
            'email' => $request->email
        ]);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => $e->getMessage()]);
    }
}

    public function storeParentWithChild(Request $request)
    {
        try {
            if (!$request->email) {
                return response()->json(['success' => false, 'message' => 'Parent email is required']);
            }
            if (!$request->child_name) {
                return response()->json(['success' => false, 'message' => 'Child name is required']);
            }

            $parentUserId = DB::table('users')->insertGetId([
                'email' => $request->email,
                'password' => bcrypt('password123'),
                'role_id' => 3,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $parentId = DB::table('parents')->insertGetId([
                'user_id' => $parentUserId,
                'full_name' => $request->parent_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $childUserId = DB::table('users')->insertGetId([
                'email' => strtolower(str_replace(' ', '.', $request->child_name)) . '@student.kinderbot.com',
                'password' => bcrypt('password123'),
                'role_id' => 3,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $studentId = DB::table('students')->insertGetId([
                'user_id' => $childUserId,
                'full_name' => $request->child_name,
                'date_of_birth' => $request->date_of_birth,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('parent_student')->insert([
                'parent_id' => $parentId,
                'student_id' => $studentId,
            ]);

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    // ==================== SECTION METHODS ====================

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

    public function getSectionStudentCount($sectionId)
    {
        $count = DB::table('enrollments')->where('section_id', $sectionId)->count();
        return response()->json(['count' => $count]);
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

    // ==================== ACTIVITY METHODS ====================

    public function getAllActivities()
    {
        $activities = DB::table('activities')->orderBy('created_at', 'desc')->get();
        return response()->json($activities);
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

public function getSection($id)
{
    try {
        $section = DB::table('sections')->where('id', $id)->first();
        return response()->json($section);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}

public function getTeacherActivityLog()
{
    $logs = DB::table('teacher_activity_log')
        ->orderBy('timestamp', 'desc')
        ->take(10)
        ->get();

    return response()->json($logs);
}
}
