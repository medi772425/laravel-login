<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// ミドルウェア guest は、ログイン前のユーザーのみがアクセスできる
Route::group(['middleware' => ['guest']], function () {
    Route::get('/', [AuthController::class, 'showLogin'])->name('login.show');

    Route::post('login', [AuthController::class, 'login'])->name('login');
});

// ミドルウェア auth は、ログイン後のユーザーのみがアクセスできる
Route::group(['middleware' => ['auth']], function () {
    // ホーム画面
    Route::get('home', function () {
        return view('home');
    })->name('home');

    // ログアウト
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
});
