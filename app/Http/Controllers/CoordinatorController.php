<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
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

private function calculateCurrentAge($dateOfBirth)
{
    if (!$dateOfBirth) return null;
    $dob = new \DateTime($dateOfBirth);
    $currentYear = (int)date('Y');

    // Use December 31st of current year as cutoff
    $cutoffDate = new \DateTime("$currentYear-12-31");

    // Calculate age on December 31st
    $age = $cutoffDate->diff($dob)->y;

    return $age;
}

private function getSuggestedClassByAge($age)
{
    // Age to grade level mapping (December 31 cutoff)
    // Age 3 → KG1 (grade_level 0)
    // Age 4 → KG2 (grade_level 1)
    // Age 5 → KG3 (grade_level 2)
    // Age 6 → Grade 1 (grade_level 3)
    // Age 7 → Grade 2 (grade_level 4)

    $gradeLevel = $age - 3; // Age 3 = grade 0, Age 4 = grade 1, etc.

    if ($gradeLevel < 0) {
        $gradeLevel = 0;
    }

    return DB::table('classes')->where('grade_level', $gradeLevel)->first();
}

    // ==================== STUDENT METHODS ====================


public function getStudents()
{
    $students = DB::table('students')
        ->leftJoin('parent_student', 'students.id', '=', 'parent_student.student_id')
        ->leftJoin('parents', 'parent_student.parent_id', '=', 'parents.id')
        ->select('students.*', 'parents.full_name as parent_name')
        ->get();

    foreach ($students as $student) {
        // Calculate age on Dec 31 for class suggestion only
        $age = $this->calculateAgeOnDec31($student->date_of_birth);
        $suggestedClass = $this->getSuggestedClassByAge($age);
        $student->suggested_class_name = $suggestedClass ? $suggestedClass->name : 'Not applicable';
        $student->is_enrolled = DB::table('enrollments')->where('student_id', $student->id)->exists();
        // Do NOT add current_age to output
    }
    return response()->json($students);
}

private function calculateAgeOnDec31($dateOfBirth)
{
    if (!$dateOfBirth) return null;
    $dob = new \DateTime($dateOfBirth);
    $currentYear = (int)date('Y');
    $cutoffDate = new \DateTime("$currentYear-12-31");
    return $cutoffDate->diff($dob)->y;
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


    public function getAvailableStudentsForSection($sectionId)
{
    $section = DB::table('sections')->where('id', $sectionId)->first();
    if (!$section) {
        return response()->json([]);
    }

    $class = DB::table('classes')->where('id', $section->class_id)->first();
    $requiredGradeLevel = $class->grade_level;

    // Calculate required age from grade level
    $requiredAge = $requiredGradeLevel + 3; // Grade 0 = age 3, Grade 1 = age 4, etc.

    $availableStudents = [];
    $allStudents = DB::table('students')->get();

    foreach ($allStudents as $student) {
        $age = $this->calculateCurrentAge($student->date_of_birth);
        $isEnrolled = DB::table('enrollments')->where('student_id', $student->id)->exists();

        // Student is eligible if age matches the required age for this grade
        if (!$isEnrolled && $age == $requiredAge) {
            //$student->current_age = $age;
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

// ==================== COORDINATOR MESSAGING METHODS ====================

// API: Get all messages for coordinator (grouped by conversation)
public function getCoordinatorMessages()
{
    $userId = Auth::id();

    // Get messages sent to coordinator
    $received = DB::table('messages')
        ->where('receiver_id', $userId)
        ->where('receiver_type', 'coordinator')
        ->whereNull('deleted_at')  // ← ADD THIS LINE
        ->orderBy('created_at', 'desc')
        ->get();

    // Get messages sent by coordinator
    $sent = DB::table('messages')
        ->where('sender_id', $userId)
        ->where('sender_type', 'coordinator')
        ->whereNull('deleted_at')  // ← ADD THIS LINE
        ->orderBy('created_at', 'desc')
        ->get();

    $messages = [];

    foreach ($received as $msg) {
        $sender = $this->getMessageUserInfo($msg->sender_id, $msg->sender_type);
        $messages[] = [
            'id' => $msg->id,
            'from' => $sender['name'],
            'from_id' => $msg->sender_id,
            'from_type' => $msg->sender_type,
            'subject' => $msg->subject,
            'message' => $msg->message,
            'date' => date('M d, Y H:i', strtotime($msg->created_at)),
            'is_read' => $msg->is_read,
            'direction' => 'received'
        ];
    }

    foreach ($sent as $msg) {
        $receiver = $this->getMessageUserInfo($msg->receiver_id, $msg->receiver_type);
        $messages[] = [
            'id' => $msg->id,
            'to' => $receiver['name'],
            'to_id' => $msg->receiver_id,
            'to_type' => $msg->receiver_type,
            'subject' => $msg->subject,
            'message' => $msg->message,
            'date' => date('M d, Y H:i', strtotime($msg->created_at)),
            'direction' => 'sent'
        ];
    }

    usort($messages, function($a, $b) {
        return strtotime($b['date']) - strtotime($a['date']);
    });

    return response()->json($messages);
}

// API: Get recipients for coordinator (all teachers and parents)
public function getCoordinatorRecipients()
{
    $recipients = [];

    // Get all teachers
    $teachers = DB::table('teachers')->get();
    foreach ($teachers as $teacher) {
        $user = DB::table('users')->where('id', $teacher->user_id)->first();
        if ($user) {
            $recipients[] = [
                'id' => $user->id,
                'name' => $teacher->full_name,
                'type' => 'teacher'
            ];
        }
    }

    // Get all parents
    $parents = DB::table('parents')->get();
    foreach ($parents as $parent) {
        $user = DB::table('users')->where('id', $parent->user_id)->first();
        if ($user) {
            $recipients[] = [
                'id' => $user->id,
                'name' => $parent->full_name,
                'type' => 'parent'
            ];
        }
    }

    return response()->json($recipients);
}

// API: Get full conversation between coordinator and a specific participant
public function getCoordinatorConversation($participantId)
{
    $userId = Auth::id();

    // Get participant type
    $participant = DB::table('users')->where('id', $participantId)->first();
    $participantType = $participant->role_id == 2 ? 'teacher' : 'parent';

    // Get all messages between coordinator and participant
    $messages = DB::table('messages')
        ->where(function($query) use ($userId, $participantId, $participantType) {
            $query->where(function($q) use ($userId, $participantId, $participantType) {
                $q->where('sender_id', $userId)
                  ->where('sender_type', 'coordinator')
                  ->where('receiver_id', $participantId)
                  ->where('receiver_type', $participantType);
            })->orWhere(function($q) use ($userId, $participantId, $participantType) {
                $q->where('sender_id', $participantId)
                  ->where('sender_type', $participantType)
                  ->where('receiver_id', $userId)
                  ->where('receiver_type', 'coordinator');
            });
        })
        ->whereNull('deleted_at')
        ->orderBy('created_at', 'asc')
        ->get();

    $conversation = [];
    foreach ($messages as $msg) {
        if ($msg->sender_type == 'coordinator') {
            $sender = 'Coordinator';
            $sender_type = 'coordinator';
        } elseif ($msg->sender_type == 'teacher') {
            $teacher = DB::table('teachers')->where('user_id', $msg->sender_id)->first();
            $sender = $teacher ? $teacher->full_name : 'Teacher';
            $sender_type = 'teacher';
        } else {
            $parent = DB::table('parents')->where('user_id', $msg->sender_id)->first();
            $sender = $parent ? $parent->full_name : 'Parent';
            $sender_type = 'parent';
        }

        $conversation[] = [
            'id' => $msg->id,
            'sender_id' => (int)$msg->sender_id,  // ← ADDED: Cast to integer
            'sender' => $sender,
            'sender_type' => $sender_type,
            'message' => $msg->message,
            'date' => date('M d, Y H:i', strtotime($msg->created_at)),
            'is_read' => $msg->is_read
        ];

        // Mark as read if coordinator is receiver
        if ($msg->receiver_id == $userId && !$msg->is_read) {
            DB::table('messages')->where('id', $msg->id)->update(['is_read' => true]);
        }
    }

    return response()->json($conversation);
}

public function sendCoordinatorMessage(Request $request)
{
    try {
        $userId = Auth::id();

        DB::table('messages')->insert([
            'sender_id' => $userId,
            'sender_type' => 'coordinator',
            'receiver_id' => $request->receiver_id,
            'receiver_type' => $request->receiver_type,
            'subject' => $request->subject ?? null,
            'message' => $request->message,
            'is_read' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json(['success' => true]);

    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => $e->getMessage()]);
    }
}


// API: Reply to a message from coordinator
public function replyToCoordinatorMessage(Request $request)
{
    try {
        $userId = Auth::id();

        DB::table('messages')->insert([
            'sender_id' => $userId,
            'sender_type' => 'coordinator',
            'receiver_id' => $request->receiver_id,
            'receiver_type' => $request->receiver_type,
            'subject' => $request->subject,
            'message' => $request->message,
            'is_read' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json(['success' => true, 'message' => 'Reply sent successfully!']);

    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => $e->getMessage()]);
    }
}

// Helper for message user info
private function getMessageUserInfo($userId, $type)
{
    $user = DB::table('users')->where('id', $userId)->first();
    $name = $user->email;

    if ($type == 'teacher') {
        $teacher = DB::table('teachers')->where('user_id', $userId)->first();
        $name = $teacher ? $teacher->full_name : $user->email;
    } elseif ($type == 'parent') {
        $parent = DB::table('parents')->where('user_id', $userId)->first();
        $name = $parent ? $parent->full_name : $user->email;
    } elseif ($type == 'coordinator') {
        $name = 'Coordinator';
    }

    return ['id' => $userId, 'name' => $name, 'type' => $type];
}


// Soft delete message
public function deleteMessage($id)
{
    try {
        $userId = Auth::id();
        $message = DB::table('messages')->where('id', $id)->first();

        if (!$message) {
            return response()->json(['success' => false, 'message' => 'Message not found']);
        }

        // Only allow deleting own messages
        if ($message->sender_id != $userId) {
            return response()->json(['success' => false, 'message' => 'Cannot delete others messages']);
        }

        // Check if already deleted
        if ($message->deleted_at) {
            return response()->json(['success'=> false, 'message' => 'Message already deleted']);
        }

        DB::table('messages')->where('id', $id)->update([
            'deleted_at' => now(),
            'updated_at' => now()
        ]);

        return response()->json(['success' => true, 'message' => 'Message deleted successfully']);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => 'Delete failed']);
    }
}

public function markMessagesAsRead($senderId)
{
    $userId = Auth::id();

    DB::table('messages')
        ->where('sender_id', $senderId)
        ->where('receiver_id', $userId)
        ->where('is_read', false)
        ->update(['is_read' => true]);

    return response()->json(['success' => true]);
}

public function getUnreadCount()
{
    $userId = Auth::id();

    $count = DB::table('messages')
        ->where('receiver_id', $userId)
        ->where('receiver_type', $this->getUserType())
        ->where('is_read', false)
        ->whereNull('deleted_at')
        ->count();

    return response()->json(['count' => $count]);
}
private function getUserType()
{
    // Determine based on controller
    if ($this instanceof CoordinatorController) return 'coordinator';
    if ($this instanceof TeacherController) return 'teacher';
    return 'parent';
}
}
