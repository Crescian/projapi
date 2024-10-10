<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\ProjectTask;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

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
    
        $notifications = [];
    
        foreach ($tasks as $task) {
            $involvedUsers = DB::table('project_task_individual_involved')
                ->where('project_task_id', $task->id)
                ->pluck('users_id');
    
            foreach ($involvedUsers as $userId) {
                $dueDate = Carbon::parse($task->due_date);
                $exists = DB::table('notifications')
                    ->where('project_id', $task->project_id)
                    ->where('users_id', $userId)
                    ->where('notification', 'DUE DATE: ' . $dueDate->format('Y-m-d') . '. You have only ' . $task->gap_in_days . ' days for this task: ' . $task->task_title)
                    ->exists();

                    if (!$exists) {
                        if ($task->gap_in_days > 0) {
                            $notifications[] = [
                                'project_id' => $task->project_id,
                                'users_id' => $userId,
                                'notification' => 'DUE DATE: ' . $dueDate->format('Y-m-d') . '. You have only ' . $task->gap_in_days . ' days for this task: ' . $task->task_title,
                                'created_at' => now(),
                                'updated_at' => now()
                            ];
                        }
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
                'id' => $projectTask->id
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
        $projectTasks = ProjectTask::where('project_id', $projectId)->get();

        if (!$projectTasks) {
            return response()->json(['message' => 'Item not found'], 404);
        }

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
        $validatedData = $request->validate([
            'project_task_status' => 'required',
        ]);

        $projectTask = ProjectTask::findOrFail($id);

        $projectTask->update([
            'project_task_status' => $validatedData['project_task_status']
        ]);

        return response()->json([
            'message' => 'Project task set to Completed successfully!',
            'project' => $projectTask
        ], 200);
    }
    public function update2(Request $request, $id)
    {
        $validatedData = $request->validate([
            'task_title' => 'required',
            'urgency' => 'required',
            'due_date' => 'required',
            'remarks' => 'required',
        ]);

        $projectTask = ProjectTask::findOrFail($id);

        $projectTask->update([
            'task_title' => $validatedData['task_title'],
            'urgency' => $validatedData['urgency'],
            'due_date' => $validatedData['due_date'],
            'remarks' => $validatedData['remarks']
        ]);

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
        $projectTask = ProjectTask::find($id);

        if (!$projectTask) {
            return response()->json([
                'message' => 'Project Task not found'
            ], Response::HTTP_NOT_FOUND);
        }

        $projectTask->delete();

        return response()->json([
            'message' => 'Project Task deleted successfully'
        ], Response::HTTP_OK);
    }
}
