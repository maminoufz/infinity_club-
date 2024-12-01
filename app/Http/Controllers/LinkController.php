<?php

namespace App\Http\Controllers;

use App\Models\Link;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class LinkController extends Controller
{
    // Show all links of a user (public access)
    public function showLinks($userId)
    {
        // Find the user's links
        $links = Link::where('user_id', $userId)->get();

        if ($links->isEmpty()) {
            return response()->json(['message' => 'No links found for this user.'], 404);
        }

        return response()->json($links, 200);
    }

    // Add a link to the authenticated user's profile
    public function addLink(Request $request)
    {
        try {
            // Check if the user is authenticated
            $user = JWTAuth::parseToken()->authenticate();

            if (!$user) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            // Validate input
            $validator = Validator::make($request->all(), [
                'name_link' => 'required|string|max:255',
                'url' => 'required|url'
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            // Create the link
            $link = new Link([
                'user_id' => $user->id,
                'name_link' => $request->name_link,
                'url' => $request->url,
            ]);

            $link->save();

            return response()->json(['message' => 'Link added successfully!', 'link' => $link], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while adding the link.'], 500);
        }
    }

    // Update a link (only the owner can update it)
    public function updateLink(Request $request, $linkId)
    {
        try {
            // Check if the user is authenticated
            $user = JWTAuth::parseToken()->authenticate();

            if (!$user) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            // Find the link by ID
            $link = Link::find($linkId);

            if (!$link) {
                return response()->json(['error' => 'Link not found'], 404);
            }

            // Check if the link belongs to the authenticated user
            if ($link->user_id !== $user->id) {
                return response()->json(['error' => 'You are not authorized to update this link.'], 403);
            }

            // Validate input
            $validator = Validator::make($request->all(), [
                'name_link' => 'required|string|max:255',
                'url' => 'required|url'
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            // Update the link
            $link->update([
                'name_link' => $request->name_link,
                'url' => $request->url,
            ]);

            return response()->json(['message' => 'Link updated successfully!', 'link' => $link], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while updating the link.'], 500);
        }
    }

    // Delete a link (only the owner can delete it)
    public function deleteLink($linkId)
    {
        try {
            // Check if the user is authenticated
            $user = JWTAuth::parseToken()->authenticate();

            if (!$user) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            // Find the link by ID
            $link = Link::find($linkId);

            if (!$link) {
                return response()->json(['error' => 'Link not found'], 404);
            }

            // Check if the link belongs to the authenticated user
            if ($link->user_id !== $user->id) {
                return response()->json(['error' => 'You are not authorized to delete this link.'], 403);
            }

            // Delete the link
            $link->delete();

            return response()->json(['message' => 'Link deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while deleting the link.'], 500);
        }
    }
}
