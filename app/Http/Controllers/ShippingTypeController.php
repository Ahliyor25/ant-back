<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShippingType\StoreShippingTypeRequest;
use App\Http\Requests\ShippingType\UpdateShippingTypeRequest;
use App\Http\Resources\ShippingTypeResource;
use App\Models\ShippingType;
use Illuminate\Support\Facades\Storage;

class ShippingTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return ShippingTypeResource::collection(
            ShippingType::query()->get()
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreShippingTypeRequest $request)
    {
        $icon = $request->file('icon')->store('images/shipping');

        ShippingType::query()->create([
            'icon' => $icon,
            'name' => $request->name,
            'key' => $request->key,
            'description' => $request->input('description'),
            'is_active' => $request->input('is_active'),
            'price' => 0,
            'service_id' => $request->input('service_id'),
        ]);
        return response()->json([
            'message' => 'Успешно создано',
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(ShippingType $shippingType)
    {
        return new ShippingTypeResource($shippingType);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateShippingTypeRequest $request, ShippingType $shippingType)
    {
        if ($request->hasFile('icon')) {
            $icon = $request->file('icon')->store('images/shipping');
            Storage::delete($shippingType->icon);
        } else {
            $icon = $shippingType->icon;
        }

        $shippingType->update([
            'icon' => $icon,
            'name' => $request->name,
            'key' => $request->input('key'),
            'description' => $request->input('description'),
            'is_active' => $request->input('is_active'),
            'service_id' => $request->input('service_id'),
        ]);

        return response()->json([
            'message' => 'Успешно изменено',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ShippingType $shippingType)
    {
        Storage::delete($shippingType->icon);
        $shippingType->delete();
    }
}
