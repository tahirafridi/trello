<?php

namespace App\Http\Controllers\Api;

use App\Models\Board;
use Illuminate\Http\Request;
use App\Http\Requests\BoardRequest;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Resources\BoardCollection;
use App\Http\Resources\BoardResource;

class BoardController extends Controller
{
    public function index()
    {
        try {
            return new BoardCollection(Board::select('id', 'title')->get());
        } catch (\Throwable $th) {
            Log::error($th->getMessage());

            return response()->json([
                'success'   => false,
                'code'      => 500,
                'message'   => "Internal server error.",
            ], 500);
        }
    }

    public function store(BoardRequest $request)
    {
        try {
            $board = Board::create(['title' => $request->title]);

            return response()->json([
                'success'   => true,
                'code'      => 200,
                'message'   => "Board created successfully.",
                'data'      => new BoardResource($board),
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

    public function destroy($id)
    {
        try {
            $board = Board::findOrFail($id);

            $board->delete();

            return response()->json([
                'success'   => true,
                'code'      => 200,
                'message'   => "Board deleted successfully.",
            ], 500);
        } catch (\Throwable $th) {
            return response()->json([
                'success'   => false,
                'code'      => 500,
                'message'   => "Internal server error.",
            ], 500);
        }
    }
}
