<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ServiceCenter;
use Illuminate\Http\JsonResponse;

class ServiceCenterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $centers = ServiceCenter::with('region')
        ->when($request->query('region_id'), fn ($q, $id) =>
            $q->where('region_id', $id))
        ->when($request->query('region'), fn ($q, $slug) =>
            $q->whereHas('region', fn ($r) => $r->where('slug', $slug)))
        ->get();
        
        return response()->json($centers);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $data = $request->validate([
            'region_id'   => 'required|exists:regions,id',
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'address'     => 'required|string|max:255',
            'phone'       => 'nullable|string|max:50',
            'email'       => 'nullable|email',
            'lat'         => 'nullable|numeric|between:-90,90',
            'lng'         => 'nullable|numeric|between:-180,180',
        ]);

        $center = ServiceCenter::create($data);
        return response()->json($center->load('region'), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(ServiceCenter $serviceCenter): JsonResponse
    {
        return response()->json($serviceCenter->load('region'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ServiceCenter $serviceCenter)
    {
        //
        
        $data = $request->validate([
            'region_id'   => 'sometimes|exists:regions,id',
            'name'        => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'address'     => 'sometimes|string|max:255',
            'phone'       => 'nullable|string|max:50',
            'email'       => 'nullable|email',
            'lat'         => 'nullable|numeric|between:-90,90',
            'lng'         => 'nullable|numeric|between:-180,180',
        ]);

        $serviceCenter->update($data);
        return response()->json([
            'message' => 'Успешно обновлено'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ServiceCenter $serviceCenter): JsonResponse
    {
        $serviceCenter->delete();
        return response()->json([], 204);
    }
}
