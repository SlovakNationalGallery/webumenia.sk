<?php

namespace App\Http\Controllers;
use App\Feedback;

class ElasticController extends Controller
{

    public function postFeedback(Request $request, $item_id) {

        $input = Input::all();
        echo $input;
        echo $item_id;
        return true;
    }
}
