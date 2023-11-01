<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\User;
use Illuminate\Http\Request;
use App\Mail\AccountVerifyMail;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    public function login(){
        return view('layout.signin');
    }

    public function signup(){
        return view('layout.signup');
    }

    public function createUser(Request $request){
        Log::info('Received data from frontend:', ['data' => $request->all()]);
        
        $formFields = $request->validate([
            'email' => ['required', 'email', Rule::unique('users', 'email')],
            'password' => 'required|min:6'
        ]);

        $formFields['password'] = bcrypt($formFields['password']);
        $user = User::create($formFields);
        auth()->login($user);

        Mail::to($user->email)->send(new AccountVerifyMail($user));

        return response()->json(['message' => 'User created successfully'], 201);
    }

    public function logout(Request $request){
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return response()->json(['message' => 'Logout successfully.'], 201);
    }
    
    public function validateUser(Request $request) {
        Log::info('Received data from frontend:', ['data' => $request->all()]);
        
        try {
            $formFields = $request->validate([
                'email' => ['required', 'email'],
                'password' => 'required'
            ]);
            
            if (auth()->attempt($formFields)) {
                $request->session()->regenerate();
                return response()->json(['message' => 'Login successfully.'], 201);
            } else {
                // Authentication failed (incorrect email or password)
                throw ValidationException::withMessages([
                    'email' => ['The provided credentials are incorrect.'],
                ]);
            }
        } catch (ValidationException $e) {
            // Handle validation errors (e.g., missing email or password)
            return response()->json(['message' => 'Validation failed.', 'errors' => $e->errors()], 422);
        }
    }
}
