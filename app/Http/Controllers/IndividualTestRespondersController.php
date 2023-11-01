<?php

namespace App\Http\Controllers;

use App\Models\Test;
use Illuminate\Http\Request;

class IndividualTestRespondersController extends Controller
{
    public function getIndividualTestResponders($id)
    {
        $test = Test::with('sets.questions.answers')->with('responders.responds')->findOrFail($id);

        return $test;
    }
    

}
