<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Set;
use App\Models\Test;
use App\Models\Answer;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class CreateNewTest extends Controller
{
    public function createTest(Request $request)
    {
        //Log::info('Received data from frontend:', ['data' => $request->all()]);

        // Validation rules
        $rules = [
            'test_name' => 'required|string|max:30',
            'passing_score' => 'required',
            'sets.*.instructions' => 'required',
            'sets.*.questions.*.question' => 'required|string',
            'sets.*.questions.*.answers.*.answer' => 'required|string',
            'sets.*.questions.*.answers.*.is_correct' => 'nullable',
        ];

        $messages = [
            'sets.*.questions.*.question.required' => 'The question field is required.',
            'sets.*.questions.*.answers.*.answer.required' => 'The answer field is required.',
            'sets.*.instructions.required' => 'The instructions field is required.'
        ];

        // Validate the request data
        $validatedData = $request->validate($rules,  $messages);

        $user = auth()->user();

        // Create the test
        $test = new Test();
        $test->user_id = $user->id;
        $test->test_name = $validatedData['test_name'];
        $test->passing_score = $validatedData['passing_score'];
        $test->status = 1; 
        $test->save();

        foreach($validatedData['sets'] as $setsData){
            $set = new Set();
            $set->test_id = $test->id;
            $set->instructions = $setsData['instructions'];
            $set->save();


            foreach ($setsData['questions'] as $questionData) {
                // Create the question
                $question = new Question();
                $question->set_id = $set->id;
                $question->question = $questionData['question'];
                $question->save();
            

                if (isset($questionData['answers'])) {
                    foreach ($questionData['answers'] as $answerData) {
                        $answer = new Answer();
                        $answer->question_id = $question->id;
                        $answer->answer = $answerData['answer'];
                        $answer->is_correct = $answerData['is_correct'];
                        $answer->save();
                    }
                }
            }
        }

        // Loop through the questions array
        

        return response()->json(['message' => 'Test created successfully'], 201);
    }
}
