<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Models\project_individual_involved;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class ProjectIndividualInvolvedController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($project_individual_involved_id)
    {
        $project_individual_involved = DB::table('project_individual_involved')
        ->join('projects', 'project_individual_involved.project_id', '=', 'projects.id')
        ->join('users', 'project_individual_involved.users_id', '=', 'users.id')
        ->where('project_individual_involved.project_id', $project_individual_involved_id)
        ->select('project_individual_involved.id','project_id','users_id','project_role','type_of_individual','first_name','last_name','middle_initial','initial') // Select specific columns if needed
        ->get();
        // Check if the item exists
        if (!$project_individual_involved) {
            return response()->json(['message' => 'Item not found'], 404);
        }
        // Return the item as a JSON response
        return response()->json($project_individual_involved, 200);
        // $project_individual_involved = project_individual_involved::orderBy('project_title', 'asc')->get();
        // return $project_individual_involved;
    }
    public function addProjectIndividualInvolved(Request $request)
    {
        try
        {
            $validateProjectInvovled = Validator::make($request->all(),
            [
                'project_id' => 'required',
                'users_id' => 'required',
                'project_role' => 'required'
            ]);
    
            if($validateProjectInvovled->fails()){
                return response()->json([
                    'status' => false,
                    'message'=> 'validation error',
                    'errors' => $validateProjectInvovled->errors()
                ],401);
            }
    
            $projectInvovled = project_individual_involved::create([
                'project_id' => $request->project_id,
                'users_id' => $request->users_id,
                'project_role' => $request->project_role
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
     * @param  \App\Models\project_individual_involved  $project_individual_involved
     * @return \Illuminate\Http\Response
     */
    
     public function show($id)
     {
         // Fetch the item by ID
         // $projectTask = ProjectTask::find($id);
        $projectIndividualInvolved = DB::table('project_individual_involved')
        ->join('projects', 'projects.id', '=', 'project_individual_involved.project_id')
        ->join('project_companies', 'project_companies.project_id', '=', 'project_individual_involved.project_id')
        ->where('project_individual_involved.users_id', '=', $id)
        ->where('project_companies.company_type', '=', 'main')
        ->select(
            'project_individual_involved.users_id',
            'project_individual_involved.project_id',
            'project_companies.company_id',
            'projects.project_title',
            'projects.urgency',
            'projects.project_requirements',
            'projects.events',
            'projects.project_next_step',
            'projects.standing_agreements'
        )
        ->distinct()
        ->get();
 
         // $projectTaskIndividual = project_task_individual_involved::where('project_task_id', $projectTaskId)->get();
 
         // Check if the item exists
         if (!$projectIndividualInvolved) {
             return response()->json(['message' => 'Item not found'], 404);
         }
         // Return the item as a JSON response
         return response()->json($projectIndividualInvolved, 200);
     }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\project_individual_involved  $project_individual_involved
     * @return \Illuminate\Http\Response
     */
    public function edit(project_individual_involved $project_individual_involved)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\project_individual_involved  $project_individual_involved
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, project_individual_involved $project_individual_involved)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\project_individual_involved  $project_individual_involved
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $validate = Validator::make($request->all(),
        [
            'project_id' => 'required',
            'users_id' => 'required'
        ]);
        // Find the project by ID
        $project_individual_involved = project_individual_involved::where('users_id', $request->users_id)
        ->where('project_id', $request->project_id)
        ->first(); // Use get() if you expect multiple records
        // $project_individual_involved = project_individual_involved::find($id);

        if (!$project_individual_involved) {
            // Return a 404 response if the project is not found
            return response()->json([
                'message' => 'Project Individual not found'
            ], Response::HTTP_NOT_FOUND);
        }

        // Delete the project
        $project_individual_involved->delete();

        // Return a 200 response indicating the project was deleted
        return response()->json([
            'message' => 'Project Individual deleted successfully'
        ], Response::HTTP_OK);
    }
}
