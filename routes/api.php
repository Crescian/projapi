<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post("register", [App\Http\Controllers\UserController::class, 'register']); //create
Route::post("login", [App\Http\Controllers\UserController::class, 'login']);

// bu_company
Route::post('getBuCompanies', [App\Http\Controllers\BUcompanyController::class, 'index']); //read
Route::post('addBuCompany', [App\Http\Controllers\BUcompanyController::class, 'addBuCompany']); //read
Route::delete('deleteBuCompany/{id}', [App\Http\Controllers\BUcompanyController::class, 'destroy']); //delete
// companies
Route::post('addCompanies', [App\Http\Controllers\CompanyController::class, 'addCompanies']); //create
Route::post('getCompanies/{id}', [App\Http\Controllers\CompanyController::class, 'show']); //create

Route::middleware('auth:sanctum')->group(function () {
    // companies
    Route::put('updateCompanyDetails/{id}', [App\Http\Controllers\CompanyController::class, 'updateCompanyDetails']); //create
    Route::delete('deleteCompany/{id}', [App\Http\Controllers\CompanyController::class, 'destroy']); //create
    //users_table
    Route::post('user', [App\Http\Controllers\UserController::class, 'index']); //read
    Route::post('getUserDetails', [App\Http\Controllers\UserController::class, 'getUserDetails']); //read
    Route::put('updateUser/{id}', [App\Http\Controllers\UserController::class, 'update']); //update
    Route::delete('deleteUser/{id}', [App\Http\Controllers\UserController::class, 'destroy']); //delete
    // projects
    Route::post('addProject', [App\Http\Controllers\ProjectController::class, 'addProject']); //create
    Route::post('project', [App\Http\Controllers\ProjectController::class, 'index']); //read
    Route::post('dashboard', [App\Http\Controllers\ProjectController::class, 'show']); //read
    Route::post('dashboardProjectStatusCount', [App\Http\Controllers\ProjectController::class, 'show2']); //read
    Route::put('projectStatus/{id}', [App\Http\Controllers\ProjectController::class, 'update']); //update
    Route::put('updateProject/{id}', [App\Http\Controllers\ProjectController::class, 'updateProjectDetails']); //update
    Route::delete('deleteProject/{id}', [App\Http\Controllers\ProjectController::class, 'destroy']); //delete
    // projects_logs
    Route::post('addProjectLogs', [App\Http\Controllers\ProjectLogsController::class, 'addProjectLogs']); //create
    Route::post('getProjectLogs/{id}', [App\Http\Controllers\ProjectLogsController::class, 'show']); //read
    // project_individual_involved
    Route::post('addProjectIndividualInvolved', [App\Http\Controllers\ProjectIndividualInvolvedController::class, 'addProjectIndividualInvolved']); //create
    Route::post('getProjectIndividualInvolved/{id}', [App\Http\Controllers\ProjectIndividualInvolvedController::class, 'index']); //read
    Route::post('getIndividualInvolvedProject/{id}', [App\Http\Controllers\ProjectIndividualInvolvedController::class, 'show']); //read
    Route::delete('deleteProjectIndividualInvolved', [App\Http\Controllers\ProjectIndividualInvolvedController::class, 'destroy']); //delete
    // project_task_individual_involved
    Route::post('addProjectTaskInvolved', [App\Http\Controllers\ProjectTaskIndividualInvolvedController::class, 'addProjectTaskInvolved']); //create
    Route::post('getTasksIndividualInvolved/{id}', [App\Http\Controllers\ProjectTaskIndividualInvolvedController::class, 'show']); //read
    Route::post('getIndividualTasksInvolved/{id}', [App\Http\Controllers\ProjectTaskIndividualInvolvedController::class, 'show2']); //read
    Route::delete('deleteIndividualTasksInvolved', [App\Http\Controllers\ProjectTaskIndividualInvolvedController::class, 'destroy']); //read
    // project_task
    Route::post('addProjectTask', [App\Http\Controllers\ProjectTaskController::class, 'addProjectTask']); //create
    Route::post('getTasks/{id}', [App\Http\Controllers\ProjectTaskController::class, 'show']); //read
    Route::put('updateProjectTask/{id}', [App\Http\Controllers\ProjectTaskController::class, 'update2']); //update
    Route::put('completeProjectTask/{id}', [App\Http\Controllers\ProjectTaskController::class, 'update']); //update status
    Route::delete('deleteProjectTask/{id}', [App\Http\Controllers\ProjectTaskController::class, 'destroy']); //delete
    Route::post('scanDueNotif', [App\Http\Controllers\ProjectTaskController::class, 'getTasksDueIn7Days']);
    // project_task_involved_activity_logs
    Route::post('addTaskInvolvedActivityLogs', [App\Http\Controllers\ProjectTaskInvolvedActivityLogsController::class, 'create']); //create
    Route::post('getTaskInvolvedActivityLogs/{id}', [App\Http\Controllers\ProjectTaskInvolvedActivityLogsController::class, 'show']); //create
    // project_companies
    Route::post('addProjectCompanies', [App\Http\Controllers\ProjectCompaniesController::class, 'addProjectCompanies']); //create
    Route::post('getProjectCompanies/{id}', [App\Http\Controllers\ProjectCompaniesController::class, 'index']); //create
    // notifications
    Route::post('getNotifications/{id}', [App\Http\Controllers\NotificationsController::class, 'show']); //create
    Route::put('updateRead/{id}', [App\Http\Controllers\NotificationsController::class, 'updateRead']); //create

});

// Individual protected route (example, if you still want it separately)
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
