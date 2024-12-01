<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Image;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;  // For external file storage

class EventController extends Controller
{
    /**
     * Get all events along with the department and image data.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllEvents()
    {
        // Fetch events with both department and image relationships
        $events = Event::with(['department', 'image'])->get();

        return response()->json(['events' => $events], 200);
    }

    /**
     * Create a new event with optional image.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addEvent(Request $request)
    {
        // Check if the user has an admin role
        try {
            $user = JWTAuth::parseToken()->authenticate();
        } catch (\Exception $e) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        if ($user->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Validate the request
        $validator = Validator::make($request->all(), [
            'type' => 'required|string|max:255',
            'date' => 'required|date',
            'description' => 'required|string|max:255',
            'id_dep' => 'required|exists:departments,id', // Ensure department exists
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        // Create the event
        $event = Event::create([
            'type' => $request->type,
            'date' => $request->date,
            'description' => $request->description,
            'id_dep' => $request->id_dep,
        ]);

        // Handle image upload if present
        if ($request->hasFile('image')) {
            // Use external storage like S3 or Aliyun OSS
            $imagePath = $request->file('image')->store('images', 'public'); // Store image in external storage
            // Save the image path in the Image table
            $image = Image::create([
                'image_path' => $imagePath,  // The stored file path (URL or path in external storage)
                'id_event' => $event->id,    // Foreign key to the event
            ]);
        }

        return response()->json(['message' => 'Event created successfully', 'event' => $event]);
    }

    /**
     * Update an existing event if the authenticated user is an admin.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateEvent(Request $request, $id)
    {
        // Check if the user has an admin role
        try {
            $user = JWTAuth::parseToken()->authenticate();
        } catch (\Exception $e) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        if ($user->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Find the event to update
        $event = Event::findOrFail($id);

        // Validate the request
        $validator = Validator::make($request->all(), [
            'type' => 'sometimes|string|max:255',
            'date' => 'sometimes|date',
            'description' => 'sometimes|string|max:255',
            'id_dep' => 'sometimes|exists:departments,id', // Ensure department exists
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        // Update the event
        $event->update($request->all());

        return response()->json(['message' => 'Event updated successfully', 'event' => $event]);
    }

    /**
     * Delete an event if the authenticated user is an admin.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteEvent($id)
    {
        // Check if the user has an admin role
        try {
            $user = JWTAuth::parseToken()->authenticate();
        } catch (\Exception $e) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        if ($user->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Find and delete the event
        $event = Event::findOrFail($id);
        $event->delete();

        return response()->json(['message' => 'Event deleted successfully']);
    }

}
