<?php

namespace App\Http\Controllers;

use App\Models\project_logs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProjectLogsController extends Controller
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

    public function addProjectLogs(Request $request) 
    {
        try
        {
            $validateProjectLogs = Validator::make($request->all(),
            [
                'project_id' => 'required',
                'events' => 'required'
            ]);
    
            if($validateProjectLogs->fails()){
                return response()->json([
                    'status' => false,
                    'message'=> 'validation error',
                    'errors' => $validateProjectLogs->errors()
                ],401);
            }
    
            $projectLogs = project_logs::create([
                'project_id' => $request->project_id,
                'events' => $request->events
            ]);
    
            return response()->json([
                'status' => true,
                'message'=> 'Project logs created successfully',
                'id' => $projectLogs->id
            ],201);
        }catch(\Throwable $th){
            return response()->json([
                'status' => false,
                'message'=> $th->getMessage(),
            ],500);
        }
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
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
     * @param  \App\Models\project_logs  $project_logs
     * @return \Illuminate\Http\Response
     */
    public function show($projectLogsId)
    {
        $projectLogs = project_logs::where('project_id', $projectLogsId)->get();

        if (!$projectLogs) {
            return response()->json(['message' => 'Item not found'], 404);
        }
        return response()->json($projectLogs, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\project_logs  $project_logs
     * @return \Illuminate\Http\Response
     */
    public function edit(project_logs $project_logs)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\project_logs  $project_logs
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, project_logs $project_logs)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\project_logs  $project_logs
     * @return \Illuminate\Http\Response
     */
    public function destroy(project_logs $project_logs)
    {
        //
    }
}
