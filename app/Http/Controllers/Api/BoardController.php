<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\BoardReorderRequest;
use App\Models\Board;
use Illuminate\Http\JsonResponse;

class BoardController extends Controller
{
    public function reorder(BoardReorderRequest $request): JsonResponse
    {
        $boards = collect($request->boards);

        $boards->each(fn ($board) => Board::where('id', $board['id'])->update(['order' => $board['order']]));

        return response()->json(['message' => 'Boards reordered successfully.']);
    }
}
