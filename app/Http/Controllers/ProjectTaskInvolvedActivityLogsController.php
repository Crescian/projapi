<?php

namespace App\Http\Controllers;

use App\Models\project_task_involved_activity_logs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProjectTaskInvolvedActivityLogsController extends Controller
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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        try
        {
            $validateProjectInvolvedActivityLogsController = Validator::make($request->all(),
            [
                'project_task_id' => 'required',
                'users_id' => 'required',
                'project_activity' => 'required',
            ]);
    
            if($validateProjectInvolvedActivityLogsController->fails()){
                return response()->json([
                    'status' => false,
                    'message'=> 'validation error',
                    'errors' => $validateProjectInvolvedActivityLogsController->errors()
                ],401);
            }
    
            $projectInvolvedActivityLogsController = project_task_involved_activity_logs::create([
                'project_task_id' => $request->project_task_id,
                'users_id' => $request->users_id,
                'project_activity' => $request->project_activity
            ]);
    
            return response()->json([
                'status' => true,
                'message'=> 'Activity Log created successfully'
            ],201);
        }catch(\Throwable $th){
            return response()->json([
                'status' => false,
                'message'=> $th->getMessage(),
            ],500);
        }
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
     * @param  \App\Models\project_task_involved_activity_logs  $project_task_involved_activity_logs
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Fetch the item by ID
        // $projectTask = ProjectTask::find($id);
        
        $project_task_involved_activity_logs = project_task_involved_activity_logs::select(
            'project_task_involved_activity_logs.id',
            'project_task_involved_activity_logs.project_task_id',
            'project_task_involved_activity_logs.project_activity',
            'users.first_name',
            'users.last_name',
            'users.middle_initial',
            'users.initial',
            'users.type_of_individual',
            'project_task_involved_activity_logs.users_id',
            'project_task_involved_activity_logs.created_at',
            'project_task_involved_activity_logs.updated_at'
        )
        ->join('users', 'users.id', '=', 'project_task_involved_activity_logs.users_id')
        ->where('project_task_involved_activity_logs.project_task_id', $id)
        ->get();
        // $project_task_involved_activity_logs = project_task_involved_activity_logs::where('project_task_id', $id)->get();


        // Check if the item exists
        if (!$project_task_involved_activity_logs) {
            return response()->json(['message' => 'Item not found'], 404);
        }

        // Return the item as a JSON response
        return response()->json($project_task_involved_activity_logs, 200);
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\project_task_involved_activity_logs  $project_task_involved_activity_logs
     * @return \Illuminate\Http\Response
     */
    public function edit(project_task_involved_activity_logs $project_task_involved_activity_logs)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\project_task_involved_activity_logs  $project_task_involved_activity_logs
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, project_task_involved_activity_logs $project_task_involved_activity_logs)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\project_task_involved_activity_logs  $project_task_involved_activity_logs
     * @return \Illuminate\Http\Response
     */
    public function destroy(project_task_involved_activity_logs $project_task_involved_activity_logs)
    {
        //
    }
}
