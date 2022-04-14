<?php

namespace App\Http\Controllers\Api;

use App\FeaturedPiece;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TrackFeaturedPieceClick extends Controller
{
    public function __invoke(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required:numerical',
        ]);

        if ($validator->fails()) {
            return response(['errors' => $validator->errors()], 400);
        }

        FeaturedPiece::query()
            ->where('id', $request->input('id'))
            ->increment('click_count');
    }
}
