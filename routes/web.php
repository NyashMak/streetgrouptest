<?php

use App\Http\Controllers\ProcessDocumentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
});

Route::post('upload', [ProcessDocumentController::class, 'upload'])->name("upload");
