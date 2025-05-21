<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShippingLocation\StoreShippingLocationRequest;
use App\Http\Requests\ShippingLocation\UpdateShippingLocationRequest;
use App\Http\Resources\ShippingLocationResource;
use App\Models\ShippingLocation;

class ShippingLocationController extends Controller
{
    public function index()
    {
        return ShippingLocationResource::collection(
            ShippingLocation::query()
                ->orderByDesc('order')
                ->get()
        );
    }

    public function store(StoreShippingLocationRequest $request)
    {
        ShippingLocation::create([
            'name' => $request->input('name'),
            'service_id' => $request->input('service_id'),
            'price' => 0,
            'is_active' => $request->input('is_active'),
            'order' => $request->input('order'),
        ]);

        return response()->json([
            'message' => 'Успешно создано'
        ], 201);
    }

    public function update(UpdateShippingLocationRequest $request, ShippingLocation $shippingLocation)
    {
        $shippingLocation->update([
            'name' => $request->input('name'),
            'service_id' => $request->input('service_id'),
            'is_active' => $request->input('is_active'),
            'order' => $request->input('order'),
        ]);

        return response()->json([
            'message' => 'Успешно обновлено'
        ]);
    }

    public function destroy(ShippingLocation $shippingLocation)
    {
        $shippingLocation->delete();

        return response()->json([
            'message' => 'Успешно удалено'
        ]);
    }
}
