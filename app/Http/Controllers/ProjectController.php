<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
// use App\Http\Controllers\DB;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessTokenResult; // Import the PersonalAccessTokenResult class


class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $projects = DB::table('projects')
        ->join('project_companies', 'project_companies.project_id', '=', 'projects.id')
        ->leftJoin('companies', 'companies.id', '=', 'project_companies.company_id')
        ->leftJoin('project_tasks', 'project_tasks.project_id', '=', 'projects.id')
        ->select(
            'projects.id as id',
            'companies.id as company_id',
            'companies.company_title',
            'projects.project_title',
            'projects.urgency',
            'projects.project_requirements',
            'projects.events',
            'projects.project_next_step',
            'projects.standing_agreements',
            'projects.project_status',
            'projects.due_date',
            'projects.created_at',
            'projects.updated_at',
            DB::raw('COALESCE(COUNT(project_tasks.id), 0) as task_count'),
            DB::raw('COALESCE(COUNT(CASE WHEN project_tasks.project_task_status = \'completed\' THEN 1 END), 0) as completed_count'),
            DB::raw('COALESCE(COUNT(CASE WHEN project_tasks.project_task_status = \'ongoing\' THEN 1 END), 0) as ongoing_count'),
            DB::raw('COALESCE(
                        (COUNT(CASE WHEN project_tasks.project_task_status = \'completed\' THEN 1 END) * 100.0 / NULLIF(COUNT(project_tasks.id), 0)), 
                        0
                    ) as completed_percentage')
        )
        ->where('project_companies.company_type', 'main')
        ->groupBy(
            'projects.id',
            'companies.id',
            'companies.company_title',
            'projects.project_title',
            'projects.urgency',
            'projects.project_requirements',
            'projects.events',
            'projects.project_next_step',
            'projects.standing_agreements',
            'projects.project_status',
            'projects.due_date',
            'projects.created_at',
            'projects.updated_at'
        )
        ->orderBy('projects.project_title', 'asc')
        ->get();
        return $projects;
    }

    public function addProject(Request $request) 
    {
        try
        {
            $validateProject = Validator::make($request->all(),
            [
                'project_title' => 'required',
                'urgency' => 'required',
                'project_requirements' => 'required',
                'events' => 'required',
                'project_next_step' => 'required',
                'standing_agreements' => 'required',
                'due_date' => 'required',
            ]);
    
            if($validateProject->fails()){
                return response()->json([
                    'status' => false,
                    'message'=> 'validation error',
                    'errors' => $validateProject->errors()
                ],401);
            }
    
            $project = Project::create([
                'project_title' => $request->project_title,
                'urgency' => $request->urgency,
                'project_requirements' => $request->project_requirements,
                'events' => $request->events,
                'project_next_step' => $request->project_next_step,
                'standing_agreements' => $request->standing_agreements,
                'due_date' => $request->due_date,
            ]);
    
            return response()->json([
                'status' => true,
                'message'=> 'Project created successfully',
                'id' => $project->id
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

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    
     public function show()
     {
        $results = Project::query()
        ->selectRaw('count(*) as projects')
        ->selectRaw('(SELECT count(*) FROM projects WHERE project_status = ?) as project_completed', ['completed'])
        ->selectRaw('(SELECT count(*) FROM projects WHERE project_status = ?) as project_in_progress', ['in_progress'])
        ->selectRaw('(SELECT count(*) FROM projects WHERE project_status = ?) as project_uninitiated', ['uninitiated'])
        ->selectRaw('(SELECT count(*) FROM projects WHERE project_status = ?) as project_onhold', ['onhold'])
        ->selectRaw('(SELECT count(*) FROM project_tasks) as project_task')
        ->selectRaw('(SELECT count(*) FROM project_tasks WHERE project_task_status = ?) as task_completed', ['completed'])
        ->selectRaw('(SELECT count(*) FROM project_tasks WHERE project_task_status = ?) as task_ongoing', ['ongoing'])
        ->first();
 
         if (!$results) {
             return response()->json(['message' => 'Item not found'], 404);
         }
         return response()->json($results, 200);
     }

     public function show2()
     {  
         $results = Project::query()
         ->selectRaw("
             COUNT(*) as total_projects,
             SUM(CASE WHEN project_status = 'uninitiated' THEN 1 ELSE 0 END) AS uninitiated,
             SUM(CASE WHEN project_status = 'in_progress' THEN 1 ELSE 0 END) AS in_progress,
             SUM(CASE WHEN project_status = 'completed' THEN 1 ELSE 0 END) AS completed,
             SUM(CASE WHEN project_status = 'onhold' THEN 1 ELSE 0 END) AS hold
         ")
         ->first();

         if (!$results) {
             return response()->json(['message' => 'Item not found'], 404);
         }
         return response()->json($results, 200);
     }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function edit(Project $project)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'project_status' => 'required',
        ]);

        $project = Project::findOrFail($id);

        $project->update([
            'project_status' => $validatedData['project_status']
        ]);

        return response()->json([
            'message' => 'Project status is set successfully!',
            'project' => $project
        ], 200);
    }
    public function updateProjectDetails(Request $request, $id)
    {
        $validatedData = $request->validate([
            'company_id' => 'required',
            'project_title' => 'required',
            'urgency' => 'required',
            'project_requirements' => 'required',
            'events' => 'required',
            'project_next_step' => 'required',
            'standing_agreements' => 'required',
            'due_date' => 'required',
        ]);

        $project = Project::findOrFail($id);

        $project->update([
            'company_id' => $validatedData['company_id'],
            'project_title' => $validatedData['project_title'],
            'urgency' => $validatedData['urgency'],
            'project_requirements' => $validatedData['project_requirements'],
            'events' => $validatedData['events'],
            'project_next_step' => $validatedData['project_next_step'],
            'standing_agreements' => $validatedData['standing_agreements'],
            'due_date' => $validatedData['due_date'],
        ]);

        return response()->json([
            'message' => 'Project updated successfully!',
            'user' => $project
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $project = Project::find($id);

        if (!$project) {
            return response()->json([
                'message' => 'Project not found'
            ], Response::HTTP_NOT_FOUND);
        }

        $project->delete();

        return response()->json([
            'message' => 'Project deleted successfully'
        ], Response::HTTP_OK);
    }
}
