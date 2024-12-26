<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
class UserController extends Controller
{
    public function index()
    {
        $users = User::all();

        if ($users->isEmpty()) {
            return response()->json(['message' => 'No data found'], 200);
        }
    
        return response()->json($users, 200);
    }

    // Create a new user
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'age'=> $request->age,
            'password' => Hash::make($request->password),
        ]);

        return response()->json($user, 201);
    }

    // Get a single user
    public function show($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        return response()->json($user, 200);
    }

    // Update a user
    public function update(Request $request, $id)
    {
        echo 'hiiii';
        // Find the user by ID
        $user = User::find($id);

        // If user is not found, return a message
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        // Validate incoming request
        $request->validate([
            'name' => 'sometimes|required|string',
            'email' => 'sometimes|required|email|unique:users,email,' . $id,
            'password' => 'sometimes|required|min:6',
        ]);

        // Update the user details
        $user->update([
            'name' => $request->name ?? $user->name,
            'email' => $request->email ?? $user->email,
            'age' => $request->age ?? $user->age,
            'password' => $request->password ? Hash::make($request->password) : $user->password,
        ]);

        // Return the updated user details
        return response()->json(['message' => 'User updated successfully', 'user' => $user], 200);
    }

    // Delete a user
    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $user->delete();
        return response()->json(['message' => 'User deleted'], 200);
    }
}
