<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class InvitationController extends Controller
{
    public function index(Request $request): Response
    {
        return response()->noContent(200);
    }

    public function store(Request $request): Response
    {
        return response()->noContent(201);
    }

    public function resend(Request $request): Response
    {
        return response()->noContent(200);
    }

    public function revoke(Request $request): Response
    {
        return response()->noContent();
    }
}
