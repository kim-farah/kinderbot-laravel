<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\CoordinatorController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\ClassController;

// Login routes
Route::get('/', function () {
    return view('login');
})->name('login');

Route::post('/login', [LoginController::class, 'authenticate'])->name('login.post');

Route::get('/logout', function () {
    session()->flush();
    return redirect()->route('login');
})->name('logout');

// Dashboard routes
Route::get('/coordinator', [CoordinatorController::class, 'dashboard'])->name('coordinator');
Route::get('/teacher', function () {
    return view('teacher');
})->name('teacher');

Route::get('/parent', function () {
    return view('parent');
})->name('parent');

// Create activity page
Route::get('/create-activity', function () {
    return view('coordinator-create');
})->name('create-activity');

// ==================== API ROUTES FOR FETCHING DATA ====================
Route::get('/api/classes', [CoordinatorController::class, 'getClasses']);
Route::get('/api/teachers', [CoordinatorController::class, 'getTeachers']);
Route::get('/api/parents', [CoordinatorController::class, 'getParents']);
Route::get('/api/sections/{classId}', [CoordinatorController::class, 'getSections']);
Route::get('/api/activities', [CoordinatorController::class, 'getAllActivities']);

// ==================== POST ROUTES FOR STORING DATA ====================
Route::post('/activities/store', [ActivityController::class, 'store'])->name('activities.store');
Route::post('/classes/store', [ClassController::class, 'store'])->name('classes.store');

// ==================== DELETE AND UPDATE ROUTES ====================
// Class routes
Route::delete('/api/classes/{id}', [CoordinatorController::class, 'deleteClass']);
Route::put('/api/classes/{id}', [CoordinatorController::class, 'updateClass']);

// Activity routes
Route::delete('/api/activities/{id}', [CoordinatorController::class, 'deleteActivity']);

// Teacher routes
Route::delete('/api/teachers/{id}', [CoordinatorController::class, 'deleteTeacher']);
Route::put('/api/teachers/{id}', [CoordinatorController::class, 'updateTeacher']);

// Parent routes
Route::delete('/api/parents/{id}', [CoordinatorController::class, 'deleteParent']);
Route::put('/api/parents/{id}', [CoordinatorController::class, 'updateParent']);

// Section management routes
Route::get('/api/sections/{classId}/details', [CoordinatorController::class, 'getSectionsWithDetails']);
Route::post('/api/sections/add', [CoordinatorController::class, 'addSection']);
Route::put('/api/sections/{id}', [CoordinatorController::class, 'updateSection']);
Route::delete('/api/sections/{id}', [CoordinatorController::class, 'deleteSection']);
Route::post('/api/sections/add-student', [CoordinatorController::class, 'addStudentToSection']);
Route::delete('/api/sections/{sectionId}/student/{studentId}', [CoordinatorController::class, 'removeStudentFromSection']);
Route::get('/api/teachers/list', [CoordinatorController::class, 'getTeachersList']);
