<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\AuthResource;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterRequest;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        try {
            $user = User::where('email', $request->email)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                return ResponseHelper::jsonResponse(false, 'Email atau password salah', null, 401);
            }

            if ($user->hasRole('Stand')) {
                $user->load('stand');

                if (!$user->stand) {
                    return ResponseHelper::jsonResponse(false, 'Akun stand tidak ditemukan', null, 401);
                }
            }

            $token = $user->createToken('api-token')->plainTextToken;

            $response = [
                'user' => new AuthResource($user),
                'token' => $token
            ];

            return ResponseHelper::jsonResponse(true, 'Login berhasil', $response, 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, 'Terjadi kesalahan sistem', null, 500);
        }
    }

    public function register(RegisterRequest $request)
    {
        try {
            $validated = $request->validated();
            $validated['password'] = bcrypt($validated['password']);

            $user = User::create($validated);

            // Check if stand data is present in the request
            if ($request->has('stand_name') && $request->has('stand_slug')) {
                // Assign Stand role
                $user->assignRole('Stand');

                $standData = $request->validate([
                    'stand_name' => 'required|string|max:255',
                    'stand_slug' => 'required|string|unique:stands,slug',
                    'stand_description' => 'nullable|string',
                ]);

                $user->stand()->create([
                    'name' => $standData['stand_name'],
                    'slug' => $standData['stand_slug'],
                    'description' => $standData['stand_description'] ?? null,
                ]);

                $user->load('stand');
            } else {
                // Assign default Student role
                $user->assignRole('Student');
            }

            $token = $user->createToken('api-token')->plainTextToken;

            $response = [
                'user' => new AuthResource($user),
                'token' => $token
            ];

            return ResponseHelper::jsonResponse(true, 'User registered successfully', $response, 201);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, 'Terjadi kesalahan saat registrasi', null, 500);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return ResponseHelper::jsonResponse(true, 'User logged out successfully', null, 200);
    }

    public function me(Request $request)
    {
        try {
            $user = $request->user();
            return ResponseHelper::jsonResponse(true, 'Data user berhasil diambil', new AuthResource($user), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, 'Terjadi kesalahan sistem', null, 500);
        }
    }
}
