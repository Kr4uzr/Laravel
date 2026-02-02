<?php

use App\Http\Controllers\Api\TaskController;
use Illuminate\Support\Facades\Route;

Route::middleware('throttle:50,1')->group(function () {
    Route::apiResource('tasks', TaskController::class);
});
