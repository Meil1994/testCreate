<?php

namespace App\Http\Controllers;


use App\Models\Test;
use App\Models\Responder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TestTakerController extends Controller
{
    public function getTestTaker(){
        $user = auth()->user();

        $responders = Responder::whereHas('test', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
            ->with('responds')
            ->latest()
            ->paginate();
    
        return $responders;
    } 

    public function getTests(){
        $test = Test::with('sets.questions.answers')->latest()->get();
    
        return $test;
    } 
    

    public function search()
    {
        $searchQuery = request('query');

        $testTakers = Responder::with('responds')->where('first_name', 'like' , "%{$searchQuery}%")->orWhere('last_name', 'like', "%{$searchQuery}%")->latest()->paginate(10);

        return response()->json($testTakers);
    }
}
