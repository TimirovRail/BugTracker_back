<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BugController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\ProjectController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:api')->get('/users', [UserController::class, 'index']);
Route::post('/users', [UserController::class, 'store']); 
Route::put('/users/{id}', [UserController::class, 'update']);
Route::delete('/users/{id}', [UserController::class, 'destroy']); 
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/bugs', [BugController::class, 'index']);
Route::post('/bugs', [BugController::class, 'store']); 
Route::put('/bugs/{id}', [BugController::class, 'update']); 
Route::delete('/bugs/{id}', [BugController::class, 'destroy']); 

Route::middleware('auth:api')->group(function () {
    Route::get('/bugs/assigned', [BugController::class, 'getAssignedBugs']);
    Route::put('/bugs/{bug}', [BugController::class, 'update']);
});
Route::middleware('auth:api')->group(function () {
    Route::post('/bugs', [BugController::class, 'store']);
});
Route::middleware('auth:sanctum')->get('/bugs/assigned', [BugController::class, 'assignedBugs']);


Route::post('/projects', [ProjectController::class, 'store']);
Route::get('/projects', [ProjectController::class, 'index']);


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::middleware('role:admin')->group(function () {
        Route::get('/admin/dashboard', [AdminController::class, 'dashboard']);
    });

    Route::middleware('role:developer,manager')->group(function () {
        Route::get('/projects', function () {
            return response()->json(['projects' => []]);
        });
    });
});
Route::post('/refresh-token', [AuthController::class, 'refreshToken']);
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/bugs', [BugController::class, 'index']);
    Route::post('/bugs', [BugController::class, 'store']);
    Route::put('/bugs/{bug}', [BugController::class, 'update']);
    Route::delete('/bugs/{bug}', [BugController::class, 'destroy']);
});
Route::middleware('auth:api')->get('/users', function () {
    return App\Models\User::all(); 
});
Route::middleware('auth:api')->get('/users', [UserController::class, 'index']);
Route::middleware('auth:sanctum')->get('/users', [UserController::class, 'index']);
Route::middleware('auth:sanctum')->get('/bugs/{bugId}/comments', [BugController::class, 'getComments']);
Route::middleware('auth:sanctum')->post('/bugs/{bugId}/comments', [BugController::class, 'addComment']);


Route::get('password/reset/{token}', function ($token) {
    return view('auth.reset-password', ['token' => $token]);
})->name('password.reset');

Route::post('password/reset', [PasswordController::class, 'resetPassword']);
Route::post('password/email', [PasswordController::class, 'sendResetLinkEmail']);

