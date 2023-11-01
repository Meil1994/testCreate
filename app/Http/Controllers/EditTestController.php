<?php

namespace App\Http\Controllers;

use App\Models\Set;
use App\Models\Test;
use App\Models\Answer;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EditTestController extends Controller
{
    public function getTestDetails($id)
    {
        $test = Test::with('sets.questions.answers')->findOrFail($id);

        return $test;
    }

    public function deleteQuestion(Question $questionId)
    {
        //Log::info('Retrieved data:', [$questionId]);
        $questionId->delete();

        return response()->json(['message' => 'Question deleted successfully!'], 201);
    }

    public function update(Request $request, $id)
    {
        //Log::info('Received data from frontend:', ['data' => $request->all()]);

        $request->validate([
            'test_name' => 'required|string|max:255', 
            'passing_score' => 'required',// Add validation rules for other fields
            'sets.*.id' => 'required',
            'sets.*.test_id' => 'required',
            'sets.*.instructions' => 'required',
            'sets.*.questions.*.id' => 'required|integer', // Assuming each question has an ID
            'sets.*.questions.*.question' => 'required|string|max:255', // Add validation rules for other fields
            'sets.*.questions.*.answers' => 'required|array', // Assuming answers is an array of objects
            'sets.*.questions.*.answers.*.id' => 'required|integer', // Assuming each answer has an ID
            'sets.*.questions.*.answers.*.answer' => 'required|string|max:255',
            'sets.*.questions.*.answers.*.is_correct' => 'required|boolean',
        ]);

        $test = Test::findOrFail($id);

        $test->update([
            'test_name' => $request->input('test_name'),
            'passing_score' => $request->input('passing_score')
        ]);

        foreach ($request->input('sets') as $setsData) {
            $sets = Set::findOrFail($setsData['id']);
            $sets->update([
                'instructions' => $setsData['instructions'],
            ]);

            foreach ($setsData['questions'] as $questionData) {
                $question = Question::findOrFail($questionData['id']);
                $question->update([
                    'question' => $questionData['question'],
                ]);

                foreach ($questionData['answers'] as $answerData) {
                    $answer = Answer::findOrFail($answerData['id']);
                    $answer->update([
                        'answer' => $answerData['answer'],
                        'is_correct' => $answerData['is_correct'],
                    ]);
                }
            }
        }

        return response()->json(['message' => 'Test updated successfully']);
    }


    public function createNewEditQuestion(Request $request)
    {
        //Log::info('Received data from frontend:', ['data' => $request->all()]);

        // Validation rules
        $rules = [
            'questions.*.set_id' => 'required',
            'questions.*.question' => 'required|string',
            'questions.*.answers.*.answer' => 'required|string',
            'questions.*.answers.*.is_correct' => 'nullable',
        ];

        $messages = [
            'questions.*.question.required' => 'The question field is required.',
            'questions.*.answers.*.answer.required' => 'The answer field is required.',
        ];

        // Validate the request data
        $validatedData = $request->validate($rules, $messages);

        // Loop through the questions array
        foreach ($validatedData['questions'] as $questionData) {
            // Create the question
            $question = new Question();
            $question->set_id = $questionData['set_id'];
            $question->question = $questionData['question'];
            $question->save();
        
            // Check if 'answers' key is present in $questionData
            if (isset($questionData['answers'])) {
                foreach ($questionData['answers'] as $answerData) {
                    // Create the answer

                    $answer = new Answer();
                    $answer->question_id = $question->id;
                    $answer->answer = $answerData['answer'];
                    $answer->is_correct = $answerData['is_correct'];
                    $answer->save();
                }
            }
        }

        return response()->json(['message' => 'Test created successfully'], 201);
    }

    public function createNewAnswer(Request $request){

        //Log::info('Received data from frontend:', ['data' => $request->all()]);

        $rules = [
            'question_id' => 'required',
            'answer' => 'required',
        ];

        $validatedData = $request->validate($rules);

        $answer = new Answer();
        $answer->question_id = $validatedData['question_id'];
        $answer->answer = $validatedData['answer'];
        $answer->is_correct = 0; 
        $answer->save();

        return response()->json(['message' => 'Answer created successfully'], 201);
    }

    public function deleteSet(Set $setId)
    {
        //Log::info('Retrieved data:', [$questionId]);
        $setId->delete();

        return response()->json(['message' => 'Set deleted successfully!'], 201);
    }

    public function createNewEditSet(Request $request)
    {
        //Log::info('Received data from frontend:', ['data' => $request->all()]);

        // Validation rules
        $rules = [
            'test_id' => 'required',
            'instructions' => 'required',
            'questions.*.question' => 'required|string',
            'questions.*.answers.*.answer' => 'required|string',
            'questions.*.answers.*.is_correct' => 'nullable',
        ];

        $messages = [
            'questions.*.question.required' => 'The question field is required.',
            'questions.*.answers.*.answer.required' => 'The answer field is required.',
        ];

        $validatedData = $request->validate($rules,  $messages);

        $set = new Set();
        $set -> test_id = $validatedData['test_id'];
        $set -> instructions = $validatedData['instructions'];
        $set->save();

        foreach($validatedData['questions'] as $questionData){
            $question = new Question();
            $question->set_id = $set->id;
            $question->question = $questionData['question'];
            $question->save();

            foreach ($questionData['answers'] as $answerData) {
                $answer = new Answer();
                $answer->question_id = $question->id;
                $answer->answer = $answerData['answer'];
                $answer->is_correct = $answerData['is_correct'];
                $answer->save();
            }
        }
        
        return response()->json(['message' => 'Set created successfully'], 201);
    }

}
