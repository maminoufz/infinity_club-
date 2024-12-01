<?php

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\SpecialiteController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\LinkController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');




Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {

    // api de auth (http//localhost:8000/api/auth/{nom de route})
    Route::put('/user/{id}', [UserController::class, 'updateUser']);
    Route::delete('/users/{id}', [UserController::class, 'deleteUser']);
    // api for auth

    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:api');

    // Add a new department (admin role required)
    Route::post('departments', [DepartmentController::class, 'store']);
    Route::put('departments/{id}', [DepartmentController::class, 'update']);
    Route::delete('departments/{id}', [DepartmentController::class, 'destroy']);

    // api de sp
    Route::post('/specialites', [SpecialiteController::class, 'store']); // Create specialite (admin only)
    Route::put('/specialites/{id}', [SpecialiteController::class, 'update']); // Update specialite (admin only)
    Route::delete('/specialites/{id}', [SpecialiteController::class, 'destroy']); // Delete specialite (admin only)

    // api events
    Route::post('events', [EventController::class, 'addEvent']); // Add event
    Route::put('events/{id}', [EventController::class, 'updateEvent']); // Update event
    Route::delete('events/{id}', [EventController::class, 'deleteEvent']); // Delete event

});
// api link

Route::middleware('auth:api')->post('/links', [LinkController::class, 'addLink']);  // Add a new link
Route::middleware('auth:api')->put('/links/{linkId}', [LinkController::class, 'updateLink']);  // Update a link
Route::middleware('auth:api')->delete('/links/{linkId}', [LinkController::class, 'deleteLink']);  // Delete a link
Route::get('/links/{userId}', [LinkController::class, 'showLinks']);  // Show all links of a user (public access)





// Get all departments whithout auth
Route::get('user', [AuthController::class, 'user'])->middleware('auth:api');

Route::get('departments', [DepartmentController::class, 'index']);
Route::get('/specialites', [SpecialiteController::class, 'index']);  // Get all specialites
Route::get('events', [EventController::class, 'getAllEvents']); // Get all events
Route::get('departments/{id}/specialites', [DepartmentController::class, 'getSpecialitesByDepartment']); // get sp belongs to dep
Route::get('specialites/{id}/users', [SpecialiteController::class, 'getUsersBySpecialite']); // get all user beglons to se
Route::get('/links/{userId}', [LinkController::class, 'showLinks']);  // Show all links of a user (public access)




