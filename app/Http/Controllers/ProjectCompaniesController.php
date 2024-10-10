<?php

namespace App\Http\Controllers;

use App\Models\project_companies;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class ProjectCompaniesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $results = \DB::table('project_companies')
        ->select('project_companies.id', 'project_companies.project_id', 'project_companies.company_id', 'companies.bu_id', 'projects.project_title', 'companies.company_title')
        ->join('projects', 'projects.id', '=', 'project_companies.project_id')
        ->join('companies', 'companies.id', '=', 'project_companies.company_id')
        ->where('project_companies.project_id', $id)
        ->get();

        return $results;
    
    }
    public function addProjectCompanies(Request $request) 
    {
        try
        {
            $validateProject = Validator::make($request->all(),
            [
                'project_id' => 'required',
                'company_id' => 'required'
            ]);
            
            if($validateProject->fails()){
                return response()->json([
                    'status' => false,
                    'message'=> 'validation error',
                    'errors' => $validateProject->errors()
                ],401);
            }
    
            $project = project_companies::create([
                'project_id' => $request->project_id,
                'company_id' => $request->company_id
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
     * @param  \App\Models\project_companies  $project_companies
     * @return \Illuminate\Http\Response
     */
    public function show(project_companies $project_companies)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\project_companies  $project_companies
     * @return \Illuminate\Http\Response
     */
    public function edit(project_companies $project_companies)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\project_companies  $project_companies
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, project_companies $project_companies)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\project_companies  $project_companies
     * @return \Illuminate\Http\Response
     */
    public function destroy(project_companies $project_companies)
    {
        //
    }
}
