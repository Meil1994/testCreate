<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    public function getUser(Request $request)
    {

        $user = auth()->user();

        return $user;
    }

    public function updateTargerUser(Request $request)
    {
        $user = Auth::user();

        // Validation rules - customize as needed
        $rules = [
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:6', // Include any password validation rules
        ];

        $request->validate($rules);

        // Update user properties
        $user->email = $request->input('email');

        if ($request->has('password')) {
            $user->password = bcrypt($request->input('password'));
        }

        $user->save();

        return response()->json(['message' => 'User updated successfully']);
    }

    public function createNote(Request $request)
    {
        Log::info('Received data from frontend:', ['data' => $request->all()]);

        $rules = [
            'title' => 'required',
            'content' => 'required',
        ];

        $validatedData = $request->validate($rules);

        $user = auth()->user();

        $note = new Note();
        $note->user_id = $user->id;
        $note->title = $validatedData['title'];
        $note->content = $validatedData['content'];
        $note->save();

        return response()->json(['message' => 'Note created successfully'], 201);
    }

    public function getNotes(){
        $user = auth()->user();

        $responders = Note::whereHas('user', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
            ->latest()
            ->get();
    
        return $responders;
    } 

    public function deleteNote(Note $noteId)
    {
        $noteId->delete();

        return response()->json(['message' => 'Note deleted successfully!'], 201);
    }
}
