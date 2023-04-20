<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PresentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

// auth
route::get('/register', [AuthController::class, 'register']);
route::post('/register', [AuthController::class, 'hitRegister']);
route::get('/login', [AuthController::class, 'login']);
route::post('/login', [AuthController::class, 'hitLogin']);
route::get('/logout', [AuthController::class, 'logout']);

// dashboard
route::get('/dashboard', [dashboardController::class, 'index']);
// user
route::get('/user', [DashboardController::class, 'user']);
route::post('/user', [DashboardController::class, 'addUser']);
route::delete('/user/{id}', [DashboardController::class, 'deleteUser']);
route::put('/user/{id}', [DashboardController::class, 'updateUser']);
// schedule
route::get('/schedule', [DashboardController::class, 'schedule']);
route::post('/schedule', [DashboardController::class, 'addschedule']);
route::delete('/schedule/{id}', [DashboardController::class, 'deleteSchedule']);
route::get('/schedule/{id}/detail', [DashboardController::class, 'detail']);

// present
route::get('/', [PresentController::class, 'index']);
route::get('/{id}', [PresentController::class, 'qr']);
route::get('/login/attend', [PresentController::class, 'attend']);
