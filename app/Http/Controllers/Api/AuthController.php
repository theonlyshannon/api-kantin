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
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        try {
            $user = User::where('email', $request->email)->first();

            if (!$user) {
                return ResponseHelper::jsonResponse(false, 'Email atau password salah', null, 401);
            }

            if (!Hash::check($request->password, $user->password)) {
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
            Log::error('Login error: ' . $e->getMessage());
            return ResponseHelper::jsonResponse(false, 'Terjadi kesalahan sistem: ' . $e->getMessage(), null, 500);
        }
    }

    public function register(RegisterRequest $request)
    {
        try {
            $validated = $request->validated();
            $validated['password'] = bcrypt($validated['password']);

            $user = User::create($validated);

            if ($request->has('stand_name') && $request->has('stand_slug')) {
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
                $user->assignRole('Student');
            }

            $token = $user->createToken('api-token')->plainTextToken;

            $response = [
                'user' => new AuthResource($user),
                'token' => $token
            ];

            return ResponseHelper::jsonResponse(true, 'Registrasi berhasil', $response, 201);
        } catch (\Exception $e) {
            Log::error('Register error: ' . $e->getMessage());
            return ResponseHelper::jsonResponse(false, 'Terjadi kesalahan saat registrasi: ' . $e->getMessage(), null, 500);
        }
    }

    public function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();
            return ResponseHelper::jsonResponse(true, 'Berhasil logout', null, 200);
        } catch (\Exception $e) {
            Log::error('Logout error: ' . $e->getMessage());
            return ResponseHelper::jsonResponse(false, 'Terjadi kesalahan saat logout', null, 500);
        }
    }

    public function me(Request $request)
    {
        try {
            $user = $request->user();
            if (!$user) {
                return ResponseHelper::jsonResponse(false, 'User tidak ditemukan', null, 401);
            }

            if ($user->hasRole('Stand')) {
                $user->load('stand');
            }

            return ResponseHelper::jsonResponse(true, 'Data user berhasil diambil', new AuthResource($user), 200);
        } catch (\Exception $e) {
            Log::error('Me endpoint error: ' . $e->getMessage());
            return ResponseHelper::jsonResponse(false, 'Terjadi kesalahan sistem: ' . $e->getMessage(), null, 500);
        }
    }
}
