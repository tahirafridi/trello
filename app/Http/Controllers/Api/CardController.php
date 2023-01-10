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
use Exception;

class CardController extends Controller
{
    public function index(Board $board)
    {
        try {
            return new CardCollection($board->cards()->select('id', 'board_id', 'title', 'description', 'order_no')->orderBy('order_no')->get());
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

    public function show(Board $board, $id)
    {
        try {
            $card = $board->cards()->where('id', $id)->first();

            if (!$card) {
                throw new Exception("Card not found.");
            }

            return response()->json([
                'success'   => true,
                'code'      => 200,
                'message'   => "Card details fetched successfully.",
                'data'      => new CardResource($card),
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success'   => false,
                'code'      => 500,
                'message'   => "Internal server error.",
            ], 500);
        }
    }

    public function update(CardRequest $request, Board $board, $id)
    {
        try {
            $card = $board->cards()->where('id', $id)->first();

            if (!$card) {
                throw new Exception("Card not found.");
            }

            $card->update([
                'title'         => $request->title,
                'description'   => $request->description,
            ]);

            return response()->json([
                'success'   => true,
                'code'      => 200,
                'message'   => "Card details updated successfully.",
                'data'      => new CardResource($card),
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success'   => false,
                'code'      => 500,
                'message'   => "Internal server error.",
            ], 500);
        }
    }

    public function updateOrder(Request $request)
    {
        try {
            if ($request->has('event')) {
                if ($request->event == 'moved') {
                    foreach ($request->cards as $index => $value) {
                        Card::where('id', $value['id'])->update(['order_no' => $index]);
                    }
                } else if ($request->event == 'added') {
                    foreach ($request->cards as $index => $value) {
                        Card::where(['id' => $value['id']])->update(['order_no' => $index, 'board_id' => $request->board['id']]);
                    }
                }
            }

            return response()->json([
                'success'   => true,
                'code'      => 200,
                'message'   => "Cards order updated.",
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

    public function destroy(Board $board, $id)
    {
        try {
            $card = $board->cards()->where('id', $id)->first();

            if (!$card) {
                throw new Exception("Card not found.");
            }

            $card->delete();

            return response()->json([
                'success'   => true,
                'code'      => 200,
                'message'   => "Card deleted successfully.",
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success'   => false,
                'code'      => 500,
                'message'   => "Internal server error.",
            ], 500);
        }
    }
}
