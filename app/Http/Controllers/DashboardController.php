<?php

namespace App\Http\Controllers;

use App\Models\Test;
use App\Models\Responder;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Assuming you have the authenticated user
        $user = auth()->user();

        // Retrieve tests that belong to the authenticated user
        $tests = Test::where('user_id', $user->id)
                    ->with('sets')
                    ->with('sets.questions')
                    ->latest()
                    ->paginate();

        return $tests;
    }
    public function updateStatus(Test $test){
        $test->update([
            'status' => request('status'),
        ]);

        return response()->json(['message' => 'Status updated successfully'], 201);
    }

    public function search(){

        $searQuery = request('query');

        $user = auth()->user();
        
        $tests = Test::where('user_id', $user->id)
                    ->with('sets')->where('test_name', 'like' , "%{$searQuery}%")
                    ->with('sets.questions')
                    ->latest()
                    ->paginate();

        return response()->json($tests);
    }

    public function deleteTest(Test $testId)
    {
        //Log::info('Retrieved data:', [$questionId]);
        $testId->delete();

        return response()->json(['message' => 'Test deleted successfully!'], 201);
    }

    public function getResultsTestTaker(){

        $responders = Responder::latest()->get();
    
        return $responders;
    } 
}
