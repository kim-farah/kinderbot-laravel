<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\CoordinatorController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\ClassController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\ParentController;
use App\Http\Controllers\AssessmentController;

Route::middleware(['web'])->group(function () {

    // Login routes
    Route::get('/', function () {
        return view('login');
    })->name('login');

    Route::post('/login', [LoginController::class, 'authenticate'])->name('login.post');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Dashboard routes
    Route::get('/coordinator', [CoordinatorController::class, 'dashboard'])->name('coordinator');

    Route::get('/create-activity', function () {
    $classes = DB::table('classes')->get();
    return view('coordinator-create', compact('classes'));
})->name('create-activity');

    // ==================== API ROUTES FOR FETCHING DATA ====================
    Route::get('/api/classes', [CoordinatorController::class, 'getClasses']);
    Route::get('/api/teachers', [CoordinatorController::class, 'getTeachers']);
    Route::get('/api/parents', [CoordinatorController::class, 'getParents']);
    Route::get('/api/activities', [CoordinatorController::class, 'getAllActivities']);

    // ==================== SECTION ROUTES ====================
    Route::get('/api/sections/{classId}', [CoordinatorController::class, 'getSections']);
    Route::get('/api/sections/{classId}/details', [CoordinatorController::class, 'getSectionsWithDetails']);
    Route::get('/api/sections/{id}', [CoordinatorController::class, 'getSection']);
    Route::post('/api/sections/add', [CoordinatorController::class, 'addSection']);
    Route::put('/api/sections/{id}', [CoordinatorController::class, 'updateSection']);
    Route::delete('/api/sections/{id}', [CoordinatorController::class, 'deleteSection']);
    Route::post('/api/sections/add-student', [CoordinatorController::class, 'addStudentToSection']);
    Route::delete('/api/sections/{sectionId}/student/{studentId}', [CoordinatorController::class, 'removeStudentFromSection']);
    Route::get('/api/sections/{sectionId}/available-students', [CoordinatorController::class, 'getAvailableStudentsForSection']);
    Route::get('/api/sections/{sectionId}/students/count', [CoordinatorController::class, 'getSectionStudentCount']);

    // ==================== POST ROUTES FOR STORING DATA ====================
    Route::post('/activities/store', [ActivityController::class, 'store'])->name('activities.store');
    Route::post('/classes/store', [ClassController::class, 'store'])->name('classes.store');

    // ==================== DELETE AND UPDATE ROUTES ====================
    Route::delete('/api/classes/{id}', [CoordinatorController::class, 'deleteClass']);
    Route::put('/api/classes/{id}', [CoordinatorController::class, 'updateClass']);
    Route::delete('/api/activities/{id}', [CoordinatorController::class, 'deleteActivity']);
    Route::delete('/api/teachers/{id}', [CoordinatorController::class, 'deleteTeacher']);
    Route::put('/api/teachers/{id}', [CoordinatorController::class, 'updateTeacher']);
    Route::post('/api/teachers/store', [CoordinatorController::class, 'storeTeacher']);
    Route::delete('/api/parents/{id}', [CoordinatorController::class, 'deleteParent']);
    Route::put('/api/parents/{id}', [CoordinatorController::class, 'updateParent']);
    Route::post('/api/parents/store', [CoordinatorController::class, 'storeParent']);
    Route::post('/api/parents/store-with-child', [CoordinatorController::class, 'storeParentWithChild']);

    Route::get('/api/teachers/list', [CoordinatorController::class, 'getTeachersList']);
    Route::get('/api/classes-with-details', [CoordinatorController::class, 'getAllClassesWithDetails']);
    Route::get('/api/classes/{id}', [CoordinatorController::class, 'getClass']);
    Route::get('/api/students/list', [CoordinatorController::class, 'getStudentsList']);

    // Student routes
    Route::get('/api/students', [CoordinatorController::class, 'getStudents']);
    Route::get('/api/students/{id}', [CoordinatorController::class, 'getStudent']);
    Route::post('/api/students/store', [CoordinatorController::class, 'storeStudent']);
    Route::put('/api/students/{id}', [CoordinatorController::class, 'updateStudent']);
    Route::delete('/api/students/{id}', [CoordinatorController::class, 'deleteStudent']);

    // Class CRUD routes
    Route::get('/api/classes', [ClassController::class, 'index']);
    Route::get('/api/classes/{id}', [ClassController::class, 'show']);
    Route::post('/api/classes', [ClassController::class, 'store']);
    Route::put('/api/classes/{id}', [ClassController::class, 'update']);
    Route::delete('/api/classes/{id}', [ClassController::class, 'destroy']);

    Route::get('/api/activities', [ActivityController::class, 'index']);
    Route::get('/api/teacher-activity-log', [CoordinatorController::class, 'getTeacherActivityLog']);

    // Teacher routes
    Route::get('/teacher', [TeacherController::class, 'dashboard'])->name('teacher');
    Route::get('/api/teacher/classes', [TeacherController::class, 'getMyClasses']);
    Route::get('/api/teacher/today-activities', [TeacherController::class, 'getTodayActivities']);
    Route::get('/api/teacher/all-activities', [TeacherController::class, 'getAllActivities']);
    Route::get('/api/teacher/assessments', [TeacherController::class, 'getAssessments']);
    Route::post('/api/teacher/assessment', [TeacherController::class, 'storeAssessment']);
    Route::get('/api/teacher/students-list', [TeacherController::class, 'getStudentsList']);
    Route::get('/api/teacher/activities-list', [TeacherController::class, 'getActivitiesList']);

    // Parent routes
    Route::get('/parent', [ParentController::class, 'dashboard'])->name('parent');
    Route::get('/api/parent/children', [ParentController::class, 'getChildren']);
    Route::get('/api/parent/child/{childId}/activities', [ParentController::class, 'getChildActivities']);
    Route::get('/api/parent/child/{childId}/progress', [ParentController::class, 'getChildProgress']);
    Route::get('/api/parent/child/{childId}/notes', [ParentController::class, 'getChildNotes']);
    Route::get('/api/parent/messages', [ParentController::class, 'getMessages']);
    Route::post('/api/parent/send-message', [ParentController::class, 'sendMessage']);

    // Password change routes
    Route::post('/api/teacher/change-password', [TeacherController::class, 'changePassword']);
    Route::post('/api/parent/change-password', [ParentController::class, 'changePassword']);

    // Teacher message routes
    Route::get('/api/teacher/messages', [TeacherController::class, 'getMessages']);
    Route::get('/api/teacher/recipients', [TeacherController::class, 'getRecipients']);
    Route::post('/api/teacher/send-message', [TeacherController::class, 'sendMessage']);

    // Parent message routes
    Route::get('/api/parent/messages', [ParentController::class, 'getMessages']);
    Route::get('/api/parent/recipients', [ParentController::class, 'getRecipients']);
    Route::post('/api/parent/send-message', [ParentController::class, 'sendMessage']);

    // Coordinator message routes
    Route::get('/api/coordinator/messages', [CoordinatorController::class, 'getCoordinatorMessages']);
    Route::get('/api/coordinator/recipients', [CoordinatorController::class, 'getCoordinatorRecipients']);
    Route::get('/api/coordinator/conversation/{participantId}', [CoordinatorController::class, 'getCoordinatorConversation']);

    // Conversation routes
    Route::get('/api/parent/conversation/{participantId}', [ParentController::class, 'getConversation']);
    Route::post('/api/parent/reply-message', [ParentController::class, 'replyToMessage']);
    Route::get('/api/teacher/conversation/{participantId}', [TeacherController::class, 'getConversation']);
    Route::post('/api/teacher/reply-message', [TeacherController::class, 'replyToMessage']);
    Route::post('/api/coordinator/send-message', [CoordinatorController::class, 'sendCoordinatorMessage']);
    Route::post('/api/coordinator/reply-message', [CoordinatorController::class, 'replyToCoordinatorMessage']);

    // Delete message routes
    Route::delete('/api/messages/{id}', [CoordinatorController::class, 'deleteMessage']);
    Route::delete('/api/teacher/messages/{id}', [TeacherController::class, 'deleteMessage']);
    Route::delete('/api/parent/messages/{id}', [ParentController::class, 'deleteMessage']);

    // Mark as read routes
    Route::post('/api/coordinator/mark-as-read/{senderId}', [CoordinatorController::class, 'markMessagesAsRead']);
    Route::post('/api/parent/mark-as-read/{senderId}', [ParentController::class, 'markMessagesAsRead']);
    Route::post('/api/teacher/mark-as-read/{senderId}', [TeacherController::class, 'markMessagesAsRead']);

    // Unread count routes
    Route::get('/api/unread-count', [CoordinatorController::class, 'getUnreadCount']);
    Route::get('/api/teacher/unread-count', [TeacherController::class, 'getUnreadCount']);
    Route::get('/api/parent/unread-count', [ParentController::class, 'getUnreadCount']);

    // Activity detail routes
    Route::get('/activities/{id}', [ActivityController::class, 'show'])->name('activities.show');
    Route::get('/api/classes/{classId}/activities', [ActivityController::class, 'byClass']);
    Route::get('/activities/{activity}/sections/{section}/assessment', [AssessmentController::class, 'show'])->name('activities.assessment');
    Route::get('/activities/{id}/assessment', [AssessmentController::class, 'index']);
    Route::get('/api/activities/{id}/assessment', [AssessmentController::class, 'getData']);
    Route::post('/api/assessment/submit', [AssessmentController::class, 'submit']);

// Add this inside your middleware group
Route::get('/api/teacher/activities', [TeacherController::class, 'getMyActivities']);
Route::get('/activities/{id}', [ActivityController::class, 'show'])->name('activities.show');

//new
Route::get('/api/activities/{id}/data', [ActivityController::class, 'getActivityData']);
});

Route::get('/activities/{id}/edit', [ActivityController::class, 'edit'])->name('activities.edit');
Route::put('/activities/{id}', [ActivityController::class, 'update'])->name('activities.update');

Route::get('/api/parent/child/{childId}/activities-with-ratings', [ParentController::class, 'getChildActivitiesWithRatings']);
