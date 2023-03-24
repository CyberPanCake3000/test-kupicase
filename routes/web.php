<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;

Route::get('/result-api', [Controller::class, 'index'])->name('index');
