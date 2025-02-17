<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use App\Helpers\ResponseHelper;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();
        return ResponseHelper::jsonResponse(true, 'Users fetched successfully', UserResource::collection($users), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserStoreRequest $request)
    {
        $request = $request->validated();

        $user = new User();
        $user->name = $request['name'];
        $user->email = $request['email'];
        $user->password = Hash::make($request['password']);
        $user->save();

        return ResponseHelper::jsonResponse(true, 'User created successfully', new UserResource($user), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::find($id);

        if (!$user) {
            return ResponseHelper::jsonResponse(false, 'User not found', null, 404);
        }

        return ResponseHelper::jsonResponse(true, 'User fetched successfully', new UserResource($user), 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserUpdateRequest $request, string $id)
    {
        $user = User::find($id);

        if (!$user) {
            return ResponseHelper::jsonResponse(false, 'User not found', null, 404);
        }

        $request = $request->validated();

        $user->name = $request['name'];
        $user->email = $request['email'];
        if (isset($request['password'])) {
            $user->password = Hash::make($request['password']);
        }
        $user->save();

        return ResponseHelper::jsonResponse(true, 'User updated successfully', new UserResource($user), 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::find($id);

        if (!$user) {
            return ResponseHelper::jsonResponse(false, 'User not found', null, 404);
        }

        $user->delete();

        return ResponseHelper::jsonResponse(true, 'User successfully deleted', null, 200);
    }
}
