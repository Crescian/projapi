<?php

namespace App\Http\Controllers;

use App\Models\project_task_individual_involved;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Response;

class ProjectTaskIndividualInvolvedController extends Controller
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

    public function addProjectTaskInvolved(Request $request)
    {
        try
        {
            $validateProjectTaskInvolved = Validator::make($request->all(),
            [
                'project_task_id' => 'required',
                'users_id' => 'required'
            ]);
    
            if($validateProjectTaskInvolved->fails()){
                return response()->json([
                    'status' => false,
                    'message'=> 'validation error',
                    'errors' => $validateProjectTaskInvolved->errors()
                ],401);
            }
    
            $projectTaskInvovled = project_task_individual_involved::create([
                'project_task_id' => $request->project_task_id,
                'users_id' => $request->users_id
            ]);
    
            return response()->json([
                'status' => true,
                'message'=> 'Project created successfully'
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
     * @param  \App\Models\project_task_individual_involved  $project_task_individual_involved
     * @return \Illuminate\Http\Response
     */
    public function show($projectTaskId)
    {
        // Fetch the item by ID
        // $projectTask = ProjectTask::find($id);
        $projectTaskIndividual = DB::table('project_task_individual_involved')
        ->join('users', 'users.id', '=', 'project_task_individual_involved.users_id')
        ->join('project_individual_involved', 'project_individual_involved.users_id', '=', 'project_task_individual_involved.users_id')
        ->where('project_task_individual_involved.project_task_id', $projectTaskId)
        ->select('project_task_individual_involved.id','project_task_individual_involved.project_task_id', 
                'project_task_individual_involved.users_id', 
                'users.first_name', 
                'users.last_name', 
                'users.middle_initial')
        ->distinct()
        ->get();


        // $projectTaskIndividual = project_task_individual_involved::where('project_task_id', $projectTaskId)->get();

        // Check if the item exists
        if (!$projectTaskIndividual) {
            return response()->json(['message' => 'Item not found'], 404);
        }
        // Return the item as a JSON response
        return response()->json($projectTaskIndividual, 200);
    }
    public function show2($projectTaskId)
    {
        // Fetch the item by ID
        // $projectTask = ProjectTask::find($id);
        $projectTaskIndividual = DB::table('project_task_individual_involved')
        ->join('project_tasks', 'project_task_individual_involved.project_task_id', '=', 'project_tasks.id')
        ->join('project_individual_involved', 'project_task_individual_involved.users_id', '=', 'project_individual_involved.users_id')
        ->where('project_task_individual_involved.users_id', $projectTaskId)
        ->select('project_tasks.id','project_tasks.project_id','project_tasks.urgency','task_title','project_tasks.due_date','project_tasks.project_task_status','remarks') // Select specific columns if needed
        // ->select('project_tasks.id','project_tasks.project_id','project_tasks.urgency','task_title','project_tasks.due_date','remarks') // Select specific columns if needed
        ->distinct()
        ->get();

        // Check if the item exists
        if (!$projectTaskIndividual) {
            return response()->json(['message' => 'Item not found'], 404);
        }
        // Return the item as a JSON response
        return response()->json($projectTaskIndividual, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\project_task_individual_involved  $project_task_individual_involved
     * @return \Illuminate\Http\Response
     */
    public function edit(project_task_individual_involved $project_task_individual_involved)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\project_task_individual_involved  $project_task_individual_involved
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, project_task_individual_involved $project_task_individual_involved)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\project_task_individual_involved  $project_task_individual_involved
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $validateProjectTaskInvolved = Validator::make($request->all(),
        [
            'project_task_id' => 'required',
            'users_id' => 'required'
        ]);
        // Find the project by ID
        
        $project_task_individual_involved = project_task_individual_involved::where('users_id', $request->users_id)
        ->where('project_task_id', $request->project_task_id)
        ->first(); // Use get() if you expect multiple records
        // $project_task_individual_involved = project_task_individual_involved::find($id);

        if (!$project_task_individual_involved) {
            // Return a 404 response if the project is not found
            return response()->json([
                'message' => 'Project task individual involved not found'
            ], Response::HTTP_NOT_FOUND);
        }

        $project_task_individual_involved->delete();

        // Return a 200 response indicating the project was deleted
        return response()->json([
            'message' => 'Project task individual involved deleted successfully'
        ], Response::HTTP_OK);
    }
}
