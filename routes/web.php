<?php

use App\Http\Controllers\TaskController;
use App\Models\Project;
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

Route::get('/', function () {
    // return view('welcome');

    $projects = Project::with('tasks')->get();
    return view('index', compact('projects'));
});

Route::put('/tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');
Route::post('/tasks/reorder', [TaskController::class, 'reorder'])->name('tasks.reorder');
