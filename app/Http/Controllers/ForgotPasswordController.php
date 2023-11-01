<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Mail\ResetPassword;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Mail\ResetPasswordMail;
use Illuminate\Support\Facades\Mail;

class ForgotPasswordController extends Controller
{
    public function forgotPassword(){
        return view('layout.forgotPassword');
    }

    public function sendResetEmail(Request $request){
        $user = User::getEmailSingle($request->email); 

        if (!empty($user)) {
            $user->remember_token = Str::random(30);
            $user->save();
            Mail::to($user->email)->send(new ResetPasswordMail($user));
            return response()->json(['message' => 'Password reset has been initiated. Please check your email.'], 201);
        }  else {
            return response()->json(['message' => 'Email not found.'], 404);
        }
    }

    public function updatePassword($remember_token)
    {
        $user = User::getTokenSingle($remember_token); 
        if (!empty($user)) {
            $data['user'] = $user;
            $data['token'] = $remember_token; 
            return view('layout.reset', $data);
        } else {
            abort(404);
        }
    }

    public function postReset($token, Request $request)
    {
        $user = User::getTokenSingle($token);
    
        if (!empty($user)) {
            $user->password = bcrypt($request->password);
            $user->remember_token = Str::random(30);
            $user->save();
    
            return response()->json(['message' => 'Password updated successfully.'], 201);
        } else {
            return response()->json(['message' => 'User not found.'], 404);
        }
    }
}
