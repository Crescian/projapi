<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessTokenResult; // Import the PersonalAccessTokenResult class

class UserController extends Controller
{
    public function index()
    {
        $User = User::all();
        return response()->json(
            [
                'User' => $User->company_title,
                'code' => 200
            ]
        );
    }
    public function register(Request $request) 
    {
        try
        {
            $validateUser = Validator::make($request->all(),
            [
                'email' => 'required|email|unique:users,email',
                'password' => 'required',
                'type_of_individual' => 'required',
                'first_name' => 'required',
                'last_name' => 'required',
                'middle_initial' => 'required',
                'position' => 'required',
                'company' => 'required',
            ]);
    
            if($validateUser->fails()){
                return response()->json([
                    'status' => false,
                    'message'=> 'validation error',
                    'errors' => $validateUser->errors()
                ],401);
            }
    
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'type_of_individual' => $request->type_of_individual,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'middle_initial' => $request->middle_initial,
                'position' => $request->position,
                'company' => $request->company,
                'initial' => $request->initial,
            ]);
    
            return response()->json([
                'status' => true,
                'message'=> 'User created successfully',
                'token' => $user->createToken("API TOKEN")->plainTextToken
            ],201);
        }catch(\Throwable $th){
            return response()->json([
                'status' => false,
                'message'=> $th->getMessage(),
            ],500);
        }
    }
    public function login(Request $request)
    {
        try
        {
            $validateUser = Validator::make($request->all(),
            [
                'email' => 'required|email',
                'password' => 'required',
            ]);
    
            if($validateUser->fails()){
                return response()->json([
                    'status' => false,
                    'message'=> 'validation error',
                    'errors' => $validateUser->errors()
                ],401);
            }

            if(!Auth::attempt($request->only(['email','password'])))
            {
                return response()->json([
                    'status' => false,
                    'message'=> 'Email & password does not match with our record',
                ],401);
            }
            $user = User::where('email',$request->email)->first();
            return response()->json([
                // 'status' => true,
                // 'message'=> 'User Logged In successfully',
                'access_token' => $user->createToken("API TOKEN")->plainTextToken,
                // 'token_type' => 'Bearer',
            ],201);
        } catch (\Throwable $th)
        {
            return response()->json([
                'status' => false,
                'message'=> $th->getMessage(),
            ],500);
        }
    }
    public function getUserDetails(Request $request)
    {
        // Find the user by email
        $user = User::where('email', $request->email)->first();
    
        // Check if user exists
        if ($user) {
            // Return user details as JSON
            return response()->json($user, 200);
        } else {
            // Return error message
            return response()->json(['error' => 'User not found'], 404);
        }
    }
}
