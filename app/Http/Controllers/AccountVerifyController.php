<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Mail\AccountVerifyMail;
use Illuminate\Support\Facades\Mail;

class AccountVerifyController extends Controller
{
    public function sendVerifyEmail() {
        $user = auth()->user(); // Get the authenticated user
    
        if ($user) {
            Mail::to($user->email)->send(new AccountVerifyMail($user));
            return response()->json(['message' => 'Email validation sent. Please check your email.'], 201);
        } else {
            return response()->json(['message' => 'User not authenticated.'], 401);
        }
    }

    public function updateVerify($id){
        $user = User::findOrFail($id);

        $user->update([
            'verified' => 1,
        ]);
        
        auth()->login($user);

        return view('layout.success');
    }
}
