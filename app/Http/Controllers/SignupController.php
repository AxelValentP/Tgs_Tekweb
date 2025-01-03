<?php

namespace App\Http\Controllers;
use App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;


class SignupController extends Controller{
public function showSignup()
{
    if (Auth::check()) {
        // Redirect the logged-in user to /app
        return redirect()->route('app');
    }
    return view('signup'); // The view with the signup form
}

public function processSignup(Request $request)
{
    // Validate the input
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8|confirmed', // ensures password confirmation field matches
    ]);

    // Create the user in the database
    $user = User::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'password' => Hash::make($validated['password']),
    ]);

    // Log the user in after registration
    Auth::login($user);

    // Redirect to the app page after successful registration
    return redirect()->route('explore');
}
}
