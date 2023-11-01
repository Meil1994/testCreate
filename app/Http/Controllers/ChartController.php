<?php

namespace App\Http\Controllers;

use App\Models\Test;
use Illuminate\Http\Request;

class ChartController extends Controller
{
    public function getChartTest(Request $request)
    {
        // Assuming you have the authenticated user
        $user = auth()->user();

        // Retrieve tests that belong to the authenticated user
        $tests = Test::where('user_id', $user->id)
                    ->with('sets')
                    ->with('sets.questions.answers')->with('responders.responds')
                    ->latest()
                    ->get();

        return $tests;
    }

    public function getIndividualChartTest($id)
    {
        $test = Test::with('sets.questions.answers')->with('responders.responds')->findOrFail($id);

        return $test;
    }
}
