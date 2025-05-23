<?php

declare(strict_types=1);

use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use Illuminate\Support\Facades\Route;

// Authentication
Route::get('/register', Register::class)->name('register');
Route::get('/login', Login::class)->name('login');