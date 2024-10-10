<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\ProjectTask;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB; // Import the DB facade

class ProjectTaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function getTasksDueIn7Days(Request $request)
    {
        // Fetch tasks due in the next 7 days
        $tasks = ProjectTask::select('*', \DB::raw('DATE(due_date) - CURRENT_DATE AS gap_in_days'))
        ->whereBetween(\DB::raw('DATE(due_date)'), [\DB::raw('CURRENT_DATE'), \DB::raw('DATE(due_date)')])
        ->get();
    
        // Initialize an array to hold notifications
        $notifications = [];
    
        // Loop through each task
        foreach ($tasks as $task) {
            // Fetch users involved with this specific task
            $involvedUsers = DB::table('project_task_individual_involved')
                ->where('project_task_id', $task->id)
                ->pluck('users_id'); // Use pluck to get an array of user IDs
    
            // Loop through each user ID
            foreach ($involvedUsers as $userId) {
                // Convert string to Carbon instance before using it
                $dueDate = Carbon::parse($task->due_date);
                // Check if a similar notification already exists
                $exists = DB::table('notifications')
                    ->where('project_id', $task->project_id)
                    ->where('users_id', $userId)
                    ->where('notification', 'DUE DATE: ' . $dueDate->format('Y-m-d') . '. You have only ' . $task->gap_in_days . ' days for this task: ' . $task->task_title)
                    ->exists();

                    // If it does not exist, prepare it for insertion
                    if (!$exists) {
                        // If gap_in_days is greater than 0
                        if ($task->gap_in_days > 0) {
                            $notifications[] = [
                                'project_id' => $task->project_id,
                                'users_id' => $userId,
                                'notification' => 'DUE DATE: ' . $dueDate->format('Y-m-d') . '. You have only ' . $task->gap_in_days . ' days for this task: ' . $task->task_title,
                                'created_at' => now(),
                                'updated_at' => now()
                            ];
                        }
                        // Else if gap_in_days is 0 (last day)
                        else if ($task->gap_in_days == 0) {
                            $notifications[] = [
                                'project_id' => $task->project_id,
                                'users_id' => $userId,
                                'notification' => 'DUE DATE: ' . $dueDate->format('Y-m-d') . '. Today is the last day for this task: ' . $task->task_title,
                                'created_at' => now(),
                                'updated_at' => now()
                            ];
                        }
                    }
                    
            }
        }
    
        // Insert all new notifications into the 'notifications' table
        if (!empty($notifications)) {
            DB::table('notifications')->insert($notifications);
        }
    
        // Return a success response
        return response()->json([
            'status' => 'success',
            'message' => 'User IDs have been inserted into the notifications table.',
        ], 200);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function addProjectTask(Request $request) 
    {
        try
        {
            $validateProjectTask = Validator::make($request->all(),
            [
                'project_id' => 'required',
                'urgency' => 'required',
                'task_title' => 'required',
                'created_at' => 'required',
                'due_date' => 'required'
            ]);
    
            if($validateProjectTask->fails()){
                return response()->json([
                    'status' => false,
                    'message'=> 'validation error',
                    'errors' => $validateProjectTask->errors()
                ],401);
            }
    
            $projectTask = ProjectTask::create([
                'project_id' => $request->project_id,
                'urgency' => $request->urgency,
                'task_title' => $request->task_title,
                'created_at' => $request->created_at,
                'due_date' => $request->due_date
            ]);
    
            return response()->json([
                'status' => true,
                'message'=> 'Project Task created successfully',
                'id' => $projectTask->id // Include the newly created task's id
            ],201);
        }catch(\Throwable $th){
            return response()->json([
                'status' => false,
                'message'=> $th->getMessage(),
            ],500);
        }
    }
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ProjectTask  $projectTask
     * @return \Illuminate\Http\Response
     */
    public function show($projectId)
    {
        // Fetch the item by ID
        // $projectTask = ProjectTask::find($id);
        $projectTasks = ProjectTask::where('project_id', $projectId)->get();

        // Check if the item exists
        if (!$projectTasks) {
            return response()->json(['message' => 'Item not found'], 404);
        }

        // Return the item as a JSON response
        return response()->json($projectTasks, 200);
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ProjectTask  $projectTask
     * @return \Illuminate\Http\Response
     */
    public function edit(ProjectTask $projectTask)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ProjectTask  $projectTask
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'project_task_status' => 'required',
            // Add other fields as necessary
        ]);

        // Find the project task by ID
        $projectTask = ProjectTask::findOrFail($id);

        // Update the 'project_task_status' with the validated data
        $projectTask->update([
            'project_task_status' => $validatedData['project_task_status']
        ]);

        // Return a response, usually a success message or the updated resource
        return response()->json([
            'message' => 'Project task set to Completed successfully!',
            'project' => $projectTask
        ], 200);
    }
    public function update2(Request $request, $id)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'task_title' => 'required',
            'urgency' => 'required',
            'due_date' => 'required',
            'remarks' => 'required',
            // Add other fields as necessary
        ]);

        // Find the project task by ID
        $projectTask = ProjectTask::findOrFail($id);

        // Update the 'project_task_status' with the validated data
        $projectTask->update([
            'task_title' => $validatedData['task_title'],
            'urgency' => $validatedData['urgency'],
            'due_date' => $validatedData['due_date'],
            'remarks' => $validatedData['remarks']
        ]);

        // Return a response, usually a success message or the updated resource
        return response()->json([
            'message' => 'Set Remarks successfully!',
            'project task' => $projectTask
        ], 200);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProjectTask  $projectTask
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Find the project by ID
        $projectTask = ProjectTask::find($id);

        if (!$projectTask) {
            // Return a 404 response if the project is not found
            return response()->json([
                'message' => 'Project Task not found'
            ], Response::HTTP_NOT_FOUND);
        }

        // Delete the project
        $projectTask->delete();

        // Return a 200 response indicating the project was deleted
        return response()->json([
            'message' => 'Project Task deleted successfully'
        ], Response::HTTP_OK);
    }
}
