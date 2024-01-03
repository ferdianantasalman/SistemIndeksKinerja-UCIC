<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', [AuthController::class, 'login']);
Route::get('/home', function () {
    return redirect('/rektorat/dashboard');
});


Route::middleware('guest')->group(function () {
    // Login
    Route::get('/login', [AuthController::class, 'login'])->name('login.index');
    Route::post('/login', [AuthController::class, 'loginStore'])->name('login');

    // Register
    Route::get('/register', [AuthController::class, 'register']);
    Route::post('/register', [AuthController::class, 'registerStore']);
});

Route::group(['middleware' => 'auth'], function () {
    Route::get('/logout', [AuthController::class, 'logout']);

    Route::prefix('rektorat')->middleware('roleAs:rektorat')->group(function () {
        Route::get('/dashboard', [HomeController::class, 'rektorat']);
    });

    Route::prefix('fakultas')->middleware('roleAs:fakultas')->group(function () {
        Route::get('/dashboard', [HomeController::class, 'fakultas']);
    });

    Route::prefix('prodi')->middleware('roleAs:prodi')->group(function () {
        Route::get('/dashboard', [HomeController::class, 'prodi']);
    });

    Route::prefix('dosen')->middleware('roleAs:dosen')->group(function () {
        Route::get('/dashboard', [HomeController::class, 'dosen']);
    });
});
