<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BoardController extends Controller
{
    public function reorder(Request $request): Response
    {
        return response()->noContent(200);
    }
}
