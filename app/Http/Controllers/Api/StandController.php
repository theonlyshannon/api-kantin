<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Stand;
use App\Http\Requests\StandStoreRequest;
use App\Http\Requests\StandUpdateRequest;
use App\Http\Resources\StandResource;
use App\Helpers\ResponseHelper;

class StandController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $stands = Stand::all();
        return ResponseHelper::jsonResponse(true, 'Stands fetched successfully', StandResource::collection($stands), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StandStoreRequest $request)
    {
        $validated = $request->validated();

        $stand = new Stand();
        $stand->user_id = $validated['user_id'];
        $stand->name = $validated['name'];
        $stand->slug = $validated['slug'];
        $stand->description = $validated['description'];
        $stand->save();

        return ResponseHelper::jsonResponse(true, 'Stand created successfully', new StandResource($stand), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $stand = Stand::find($id);

        if (!$stand) {
            return ResponseHelper::jsonResponse(false, 'Stand not found', null, 404);
        }

        return ResponseHelper::jsonResponse(true, 'Stand fetched successfully', new StandResource($stand), 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StandUpdateRequest $request, string $id)
    {
        $stand = Stand::find($id);

        if (!$stand) {
            return ResponseHelper::jsonResponse(false, 'Stand not found', null, 404);
        }

        $validated = $request->validated();
        $stand->user_id = $validated['user_id'];
        $stand->name = $validated['name'];
        $stand->slug = $validated['slug'];
        $stand->description = $validated['description'];
        $stand->save();

        return ResponseHelper::jsonResponse(true, 'Stand updated successfully', new StandResource($stand), 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $stand = Stand::find($id);

        if (!$stand) {
            return ResponseHelper::jsonResponse(false, 'Stand not found', null, 404);
        }

        $stand->delete();

        return ResponseHelper::jsonResponse(true, 'Stand successfully deleted', null, 200);
    }
}
