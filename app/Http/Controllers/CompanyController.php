<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $Company = Company::all()->pluck('bu_title');
        // return response()->json(
        //     [
        //         'id' => $Company,
        //         'bu_title' => $Company,
        //         'code' => 200
        //     ]
        // );
        // $Company = Company::orderBy('bu_title', 'asc')->get();
        // return $Company;
    }
    
    public function addCompanies(Request $request) 
    {
        try
        {
            $validateProjectTask = Validator::make($request->all(),
            [
                'bu_id' => 'required',
                'company_title' => 'required'
            ]);
    
            if($validateProjectTask->fails()){
                return response()->json([
                    'status' => false,
                    'message'=> 'validation error',
                    'errors' => $validateProjectTask->errors()
                ],401);
            }
    
            $Company = Company::create([
                'bu_id' => $request->bu_id,
                'company_title' => $request->company_title
            ]);
    
            return response()->json([
                'status' => true,
                'message'=> 'Company created successfully',
                'id' => $Company->id // Include the newly created task's id
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
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function show($buId)
    {
        // Fetch the item by ID
        // $projectTask = ProjectTask::find($id);
        $companyy = Company::where('bu_id', $buId)->orderBy('company_title', 'asc')->get();


        // Check if the item exists
        if (!$companyy) {
            return response()->json(['message' => 'Item not found'], 404);
        }

        // Return the item as a JSON response
        return response()->json($companyy, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */

    public function updateCompanyDetails(Request $request, $id)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'company_title' => 'required'
            // Add other fields as necessary
        ]);

        // Find the company details by ID
        $company_details = Company::findOrFail($id);

        // Company details the project with the validated data
        $company_details->update([
            'company_title' => $validatedData['company_title']
        ]);

        // Return a response, usually a success message or the updated resource
        return response()->json([
            'message' => 'Company details updated successfully!',
            'user' => $company_details
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Company $company)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    
     public function destroy($id)
     {
         // Find the project by ID
         $company = Company::find($id);
 
         if (!$company) {
             // Return a 404 response if the project is not found
             return response()->json([
                 'message' => 'Company not found'
             ], Response::HTTP_NOT_FOUND);
         }
 
         // Delete the project
         $company->delete();
 
         // Return a 200 response indicating the project was deleted
         return response()->json([
             'message' => 'Company deleted successfully'
         ], Response::HTTP_OK);
     }
}
