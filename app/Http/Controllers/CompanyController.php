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
                'id' => $Company->id
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
        $companyy = Company::where('bu_id', $buId)->orderBy('company_title', 'asc')->get();

        if (!$companyy) {
            return response()->json(['message' => 'Item not found'], 404);
        }

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
        $validatedData = $request->validate([
            'company_title' => 'required'
        ]);

        $company_details = Company::findOrFail($id);

        $company_details->update([
            'company_title' => $validatedData['company_title']
        ]);

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
         $company = Company::find($id);
 
         if (!$company) {
             return response()->json([
                 'message' => 'Company not found'
             ], Response::HTTP_NOT_FOUND);
         }
 
         $company->delete();
 
         return response()->json([
             'message' => 'Company deleted successfully'
         ], Response::HTTP_OK);
     }
}
