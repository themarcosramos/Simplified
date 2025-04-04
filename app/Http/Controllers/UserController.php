<?php

namespace App\Http\Controllers;
use App\Models\User;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return response()->json($users);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'cpf_cnpj' => 'required|unique:users,cpf_cnpj',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'is_merchant' => 'required|boolean'
        ]);

        $user = new User([
            'name' => $request->name,
            'cpf_cnpj' => $request->cpf_cnpj,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'is_merchant' => $request->is_merchant,
            'balance' => 0.00
        ]);
        $user->save();

        return response()->json(['message' => 'User successfully created', 'data' => $user], 201);
    }

    public function show($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        return response()->json($user);
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $user->update($request->all());
        return response()->json(['message' => 'User updated successfully', 'data' => $user]);
    }

    public function destroy($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $user->delete();
        return response()->json(['message' => 'User deleted successfully']);
    }
}
