<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    /**
     * Register a new user.
     */
    public function register(Request $request)
    {
        // Validate the input data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:15',
            'bio' => 'required|string|min:8',
            'id_sp' => 'nullable|integer|exists:specialites,id', // Validate id_sp
        ]);

        // If validation fails, return the error messages
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Create a new user if validation passes
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'bio' => $request->bio,
            'id_sp' => $request->id_sp,
        ]);

        // Create JWT token for the newly registered user
        $token = JWTAuth::fromUser($user);

        // Return a success message with the user data and token
        return response()->json([
            'message' => 'Utilisateur créé avec succès.',
            'user' => $user,
            'token' => $token,
        ], 201);
    }

    /**
     * Login a user and return a JWT token.
     */
    public function login(Request $request)
    {
        // Validate the input data
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8',
        ]);

        // If validation fails, return the error messages
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Attempt to log the user in and create a token
        try {
            if (!$token = JWTAuth::attempt($request->only('email', 'password'))) {
                return response()->json(['error' => 'Identifiants incorrects.'], 401);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'Could not create token, please try again.'], 500);
        }

        // Get authenticated user
        $user = JWTAuth::user();

        // Return the user and the token in the response
        return response()->json([
            'message' => 'Connexion réussie.',
            'user' => $user,
            'token' => $token,
        ]);
    }

    /**
     * Logout a user by invalidating the token.
     */
    public function logout(Request $request)
    {
        try {
            // Invalidate the token (log out the user)
            JWTAuth::invalidate(JWTAuth::getToken());

            return response()->json(['message' => 'Déconnexion réussie.']);
        } catch (JWTException $e) {
            return response()->json(['error' => 'Could not invalidate token, please try again.'], 500);
        }
    }

    /**
     * Get the authenticated user's details.
     */
    public function user(Request $request)
    {
        // Get the authenticated user from the JWT token
        $user = JWTAuth::user();

        return response()->json([
            'user' => $user,
        ]);
    }
}
