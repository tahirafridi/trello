<?php

namespace App\Http\Controllers\Api;

use App\Models\Card;
use App\Models\Board;
use Illuminate\Http\Request;
use App\Http\Requests\CardRequest;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Resources\CardResource;
use App\Http\Resources\CardCollection;

class CardController extends Controller
{
    public function index(Board $board)
    {
        try {
            return new CardCollection($board->cards()->select('id', 'board_id', 'title', 'description')->get());
        } catch (\Throwable $th) {
            Log::error($th->getMessage());

            return response()->json([
                'success'   => false,
                'code'      => 500,
                'message'   => "Internal server error.",
            ], 500);
        }
    }

    public function store(CardRequest $request, Board $board)
    {
        try {
            $card = $board->cards()->create([
                'title'         => $request->title,
                'description'   => $request->description,
            ]);

            return response()->json([
                'success'   => true,
                'code'      => 200,
                'message'   => "Card created successfully.",
                'data'      => new CardResource($card),
            ], 200);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());

            return response()->json([
                'success'   => false,
                'code'      => 500,
                'message'   => "Internal server error.",
            ], 500);
        }
    }

    public function show($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
