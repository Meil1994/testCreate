<?php

namespace App\Http\Controllers;

use App\Models\Test;
use App\Models\Responder;
use Illuminate\Http\Request;

class IndividualResponseController extends Controller
{
    public function getIndividualResponse($id)
    
    {
       $responder = Responder::with('responds')->findOrFail($id);

        return $responder;
    }

    public function getIndividualTest($id)
    
    {
        $tests = Test::with('sets.questions.answers')->findOrFail($id);

        return $tests;
    }

    public function updateResponderStatus(Responder $responder){
        $responder->update([
            'status' => request('status'),
        ]);

        return response()->json(['message' => 'Status updated successfully'], 201);
    }
}
