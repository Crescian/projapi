<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessTokenResult;

class UserController extends Controller
{
    public function index()
    {
        $User = User::orderBy('first_name', 'asc')->get();
        return $User;
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
                'access_token' => $user->createToken("API TOKEN")->plainTextToken,
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
        $user = User::where('email', $request->email)->first();
    
        if ($user) {
            return response()->json($user, 200);
        } else {
            return response()->json(['error' => 'User not found'], 404);
        }
    }
    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'message' => 'User not found'
            ], Response::HTTP_NOT_FOUND);
        }

        $user->delete();

        return response()->json([
            'message' => 'User deleted successfully'
        ], Response::HTTP_OK);
    }
    public function update(Request $request, $id)
    {
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
        ]);

        $user = User::findOrFail($id);

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

        return response()->json([
            'message' => 'User updated successfully!',
            'user' => $user
        ], 200);
    }

}
