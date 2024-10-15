<?php

use App\Models\Task;
use App\Livewire\Login;
use App\Models\Project;
use Illuminate\Http\Request;
use App\Livewire\Tasks\TasksBoard;
use App\Livewire\Users\UserListing;
use App\Livewire\Tasks\TasksListing;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Livewire\Worklogs\WorklogListing;
use App\Http\Middleware\AccessLevelSenior;
use App\Livewire\Projects\ProjectsListing;
use App\Livewire\Password\RequestForResetPassword;
use App\Livewire\Projects\Reports as ProjectReport;
use App\Livewire\Projects\Details as ProjectDetails;
use App\Livewire\Password\ApproveResetPasswordRequest;

Route::get('/', Login::class)->middleware('guest')->name('login');
Route::get('forgot-password', RequestForResetPassword::class)->name('password.reset');
Route::get('reset-password', ApproveResetPasswordRequest::class)->name('password.approve');


Route::middleware('auth')->group(function(){
    Route::view('dashboard', 'dashboard')->name('dashboard');

    Route::get('users', UserListing::class)->name('users.listing')->middleware([AccessLevelSenior::class]);
    Route::get('projects', ProjectsListing::class)->name('projects.listing')->middleware([AccessLevelSenior::class]);

    Route::get('projects/details/{project}/worklogs', WorklogListing::class)->name('projects.worklogs.listing')->middleware([AccessLevelSenior::class]);
    Route::get('projects/details/{project}', ProjectDetails::class)->name('projects.details');
    Route::get('projects/details/{project}/reports', ProjectReport::class)->name('projects.details.reports')->middleware([AccessLevelSenior::class]);


    Route::get('tasks/details/{task:id}', function(Request $request, Task $task){
        if(!request()->filled('project')){
            abort(404);
        }
        return view('task-details', compact('task'));
    })->name('tasks.details');

    Route::get('tasks/board', TasksBoard::class)->name('tasks-board');
    Route::get('tasks', TasksListing::class)->name('tasks.listing');

    Route::any('logout', function(){ Auth::logout(); return to_route("login"); })->name('logout');
});
