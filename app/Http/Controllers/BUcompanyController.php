<?php

namespace App\Http\Controllers;

use App\Models\BUcompany;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class BUcompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $BUcompany = BUcompany::orderBy('bu_title', 'asc')->get();
        return $BUcompany;
    }

    public function addBuCompany(Request $request) 
    {
        try
        {
            $validateBuCompany = Validator::make($request->all(),
            [
                'bu_title' => 'required'
            ]);
    
            if($validateBuCompany->fails()){
                return response()->json([
                    'status' => false,
                    'message'=> 'validation error',
                    'errors' => $validateBuCompany->errors()
                ],401);
            }
    
            $buCompany = BUcompany::create([
                'bu_title' => $request->bu_title
            ]);
    
            return response()->json([
                'status' => true,
                'message'=> 'BU company Task created successfully',
                'id' => $buCompany->id // Include the newly created task's id
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
     * @param  \App\Models\BUcompany  $bUcompany
     * @return \Illuminate\Http\Response
     */
    public function show(BUcompany $bUcompany)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BUcompany  $bUcompany
     * @return \Illuminate\Http\Response
     */
    public function edit(BUcompany $bUcompany)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BUcompany  $bUcompany
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BUcompany $bUcompany)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BUcompany  $bUcompany
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Find the project by ID
        $BUcompany = BUcompany::find($id);

        if (!$BUcompany) {
            // Return a 404 response if the project is not found
            return response()->json([
                'message' => 'BU Company not found'
            ], Response::HTTP_NOT_FOUND);
        }

        // Delete the project
        $BUcompany->delete();

        // Return a 200 response indicating the project was deleted
        return response()->json([
            'message' => 'BU Company deleted successfully'
        ], Response::HTTP_OK);
    }
}
