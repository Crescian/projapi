<?php

namespace App\Http\Controllers;

use App\Models\notifications;
use Illuminate\Http\Request;

class NotificationsController extends Controller
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
     * @param  \App\Models\notifications  $notifications
     * @return \Illuminate\Http\Response
     */
    public function show($usersId)
    {
        // Fetch the item by ID
        // $projectTask = ProjectTask::find($id);
        $notification = notifications::where('users_id', $usersId)->get();

        // Check if the item exists
        if (!$notification) {
            return response()->json(['message' => 'Item not found'], 404);
        }

        // Return the item as a JSON response
        return response()->json($notification, 200);
        
        return response()->json(['message' => 'Scan successfully'], 404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\notifications  $notifications
     * @return \Illuminate\Http\Response
     */
    public function edit(notifications $notifications)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\notifications  $notifications
     * @return \Illuminate\Http\Response
     */
    public function updateRead($id)
    {
        // Find the project by ID
        $notifications = notifications::findOrFail($id);

        // Update the notifications with the validated data
        $notifications->update([
            'read' => true,
        ]);

        // Return a response, usually a success message or the updated resource
        return response()->json([
            'message' => 'Notifications updated successfully!',
            'output' => $notifications
        ], 200);
    }
    public function update(Request $request, notifications $notifications)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\notifications  $notifications
     * @return \Illuminate\Http\Response
     */
    public function destroy(notifications $notifications)
    {
        //
    }
}
