<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function authOperator(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
                'device_name' => 'required',
            ]);

            $user = User::where('email', $request->email)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                throw ValidationException::withMessages([
                    'password' => ['Periksa kembali PIN anda'],
                ]);
            }

            $user->tokens()->delete();
            $token = $user->createToken($request->device_name, ['ticket:transaction'])->plainTextToken;
            return response()->json([
                'token' => $token,
                'message' => 'Authenticated'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'token' => null,
                'message' => $th->getMessage()
            ], 422);
        }
    }
}
