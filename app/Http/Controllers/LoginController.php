<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class LoginController extends Controller
{
   
   
public function showLoginForm()
{
    // Check if the user is already logged in
    if (Auth::check()) {
        // Redirect the logged-in user to /app
        return redirect()->route('app');
    }

    // Show the login form if the user is not logged in
    return view('login');
}

  
    public function login(Request $request)
    {
        // Validate the login form data
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // Attempt to log the user in with the given credentials
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password], $request->remember)) {
            // Redirect user to intended page after successful login
            return redirect()->intended(route('load')); // replace with the route you want to redirect to
        }

        // If authentication fails, redirect back with error message
        return back()->withErrors([
            'email' => 'The provided credentials are incorrect.',
        ])->withInput($request->only('email', 'remember'));
    }

    
 
    public function logout()
    {
        Auth::logout(); // Log the user out

        // Redirect to the login page after logout
        return redirect(route('login')); // Replace with your login route
    }
}
