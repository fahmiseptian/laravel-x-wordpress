<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UsersController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }
    public function add()
    {
        $users = User::all();
        return view('users.add', compact('users'));
    }
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'username' => 'required|string|unique:users',
            'password' => 'required|string|min:6',
        ]);

        $user = User::register($validated);

        return response()->json(['message' => 'User registered', 'user' => $user], 201);
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'old_password' => 'required|string',
            'new_password' => 'required|string|min:6|confirmed',
            'id' => 'required|integer',
        ]);

        $changed = User::changePassword($request->id, $request->old_password, $request->new_password);

        if (!$changed) {
            return response()->json(['message' => 'Password lama salah'], 403);
        }

        return response()->json(['message' => 'Password berhasil diubah']);
    }

    public function checkStatus(Request $request)
    {
        $identifier = $request->input('identifier');
        $user = User::checkStatus($identifier);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        return response()->json(['status' => $user->status]);
    }

    public function edit($id)
    {
        $user = User::editUser($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        return response()->json($user);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'sometimes|required|string|max:255',
            'id' => 'required|integer',
            'email'    => 'sometimes|required|email|unique:users,email,' . $request->id,
            'username' => 'sometimes|required|string|unique:users,username,' . $request->id,
        ]);

        $user = User::updateUser($request->id, $validated);

        return response()->json(['message' => 'User updated', 'user' => $user]);
    }

    public function updateStatus(Request $request)
    {
        $request->validate([
            'status' => 'required|in:active,inactive',
            'id' => 'required|integer',
        ]);

        $user = User::updateStatus($request->id, $request->status);

        return response()->json(['message' => 'Status updated', 'user' => $user]);
    }

    public function detail($id)
    {
        $user = User::getDetail($id);

        if (!$user) {
            return redirect()->route('users.index')->withErrors(['message' => 'User not found']);
        }

        return view('users.edit', compact('user'));
    }
}
