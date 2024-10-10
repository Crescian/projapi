<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessTokenResult; // Import the PersonalAccessTokenResult class

class UserController extends Controller
{
    public function index()
    {
        $User = User::orderBy('first_name', 'asc')->get();
        return $User;
        // return response()->json(
        //     [
        //         'User' => $User->company_title,
        //         'code' => 200
        //     ]
        // );
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
                'contact_number' => 'required',
            ]);
    
            if($validateUser->fails()){
                return response()->json([
                    'status' => false,
                    'message'=> 'validation error',
                    'errors' => $validateUser->errors()
                ],401);
            }
    
            $user = User::create([
                // 'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'type_of_individual' => $request->type_of_individual,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'middle_initial' => $request->middle_initial,
                'position' => $request->position,
                'company' => $request->company,
                'initial' => $request->initial,
                'contact_number' => $request->contact_number,
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
                // 'email' => 'required|email',
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
    public function destroy($id)
    {
        // Find the project by ID
        $user = User::find($id);

        if (!$user) {
            // Return a 404 response if the project is not found
            return response()->json([
                'message' => 'User not found'
            ], Response::HTTP_NOT_FOUND);
        }

        // Delete the project
        $user->delete();

        // Return a 200 response indicating the project was deleted
        return response()->json([
            'message' => 'User deleted successfully'
        ], Response::HTTP_OK);
    }
    public function update(Request $request, $id)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'email' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'middle_initial' => 'required',
            'position' => 'required',
            'contact_number' => 'required',
            'department' => 'required',
            'initial' => 'required',
            'type_of_individual' => 'required',
            'company' => 'required',
            // Add other fields as necessary
        ]);

        // Find the user by ID
        $user = User::findOrFail($id);

        // Update the user with the validated data
        $user->update([
            'email' => $validatedData['email'],
            'first_name' => $validatedData['first_name'],
            'last_name' => $validatedData['last_name'],
            'middle_initial' => $validatedData['middle_initial'],
            'position' => $validatedData['position'],
            'contact_number' => $validatedData['contact_number'],
            'department' => $validatedData['department'],
            'initial' => $validatedData['initial'],
            'type_of_individual' => $validatedData['type_of_individual'],
            'company' => $validatedData['company'],
        ]);

        // Return a response, usually a success message or the updated resource
        return response()->json([
            'message' => 'User updated successfully!',
            'user' => $user
        ], 200);
    }

}
