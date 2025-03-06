<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Stand;

class ProfileController extends Controller
{
    public function update(ProfileUpdateRequest $request)
    {
        try {
            $user = Auth::user();

            $profile = Stand::query()->where('user_id', $user->id)->first();

            if (!$profile) {
                return ResponseHelper::jsonResponse(false, 'Profile not found', null, 404);
            }

            $profile->update($request->validated());

            return ResponseHelper::jsonResponse(true, 'Profile updated successfully', $profile, 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, 'Failed to update profile', null, 500);
        }
    }
}
