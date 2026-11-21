<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function create(): View
    {
        return view('auth.signin');
    }

    public function registration(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        return response()->json([
            'message' => 'Данные успешно проверены.',
            'data' => [
                'name' => $validated['name'],
                'email' => $validated['email'],
            ],
        ]);
    }
}
