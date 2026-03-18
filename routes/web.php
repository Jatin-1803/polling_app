<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\PollController;
use App\Http\Controllers\PublicController;

Route::get('/', function(){ return redirect()->route('polls.public.index'); });

// Admin auth
Route::get('admin/register', [AuthController::class,'showRegister'])->name('admin.register');
Route::post('admin/register', [AuthController::class,'register'])->name('admin.register.post');
Route::get('admin/login', [AuthController::class,'showLogin'])->name('admin.login');
Route::post('admin/login', [AuthController::class,'login'])->name('admin.login.post');
Route::post('admin/logout', [AuthController::class,'logout'])->name('admin.logout');

// Admin polls
Route::prefix('admin/polls')->name('admin.polls.')->middleware('auth','admin')->group(function(){
    Route::get('/', [PollController::class,'index'])->name('index');
    Route::get('create', [PollController::class,'create'])->name('create');
    Route::post('/', [PollController::class,'store'])->name('store');
    Route::get('{uuid}', [PollController::class,'show'])->name('show');
});

// Public poll view & vote endpoint
Route::get('polls', [PublicController::class,'index'])->name('polls.public.index');
Route::get('polls/{uuid}', [PublicController::class,'show'])->name('polls.public.show');
Route::post('polls/{uuid}/vote', [PublicController::class,'vote'])->name('polls.public.vote');