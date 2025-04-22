<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'dob' => 'required|date',
            'company_name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'zip_code' => 'required|string|max:10',
        ]);

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'dob' => $request->dob,
            'company_name' => $request->company_name,
            'address' => $request->address,
            'country' => $request->country,
            'city' => $request->city,
            'zip_code' => $request->zip_code,
        ]);



        return response()->json(['message' => 'User registered successfully'], 201);
    }

    public function renderRegistration(Request $request)
    {
        return view('auth.register');
    }
}
