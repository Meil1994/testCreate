<?php

namespace App\Http\Controllers;

use App\Models\Test;
use App\Models\Respond;
use App\Models\Responder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TestAnswersController extends Controller
{
    public function getTestAnswer($id)
    {

        $test = Test::with('sets.questions.answers')->findOrFail($id);

        return $test;
    }

    public function createNewAnswers(Request $request)
    {
        //Log::info('Received data from frontend:', ['data' => $request->all()]);

        $rules = [
            'first_name' => 'required|string|max:20',
            'last_name' => 'required|string|max:20',
            'test_id' => 'required',
            'responds.*.question' => 'required',
            'responds.*.answer' => 'required',
        ];


        // Validate the request data
        $validatedData = $request->validate($rules);

        // Create the responder
        $responder = new Responder();
        $responder->first_name = $validatedData['first_name'];
        $responder->last_name = $validatedData['last_name'];
        $responder->test_id = $validatedData['test_id'];
        $responder->save();

        // Loop through the responds array
        foreach ($validatedData['responds'] as $respondData) {
            // Create the question
            $respond = new Respond();
            $respond->responder_id = $responder->id;
            $respond->question = $respondData['question'];
            $respond->answer = $respondData['answer'];
            $respond->save();
        
        }

        return response()->json(['message' => 'Answer submitted successfully'], 201);

        
    }



}
