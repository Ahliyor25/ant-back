<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\AdvertisingRate\StoreAdvertisingRateRequest;
use App\Http\Requests\AdvertisingRate\UpdateAdvertisingRateRequest;
use App\Models\AdvertisingRate;
use App\Models\Language;
use App\Http\Resources\AdvertisingRateResource;


class AdvertisingRateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $advertisingRates = AdvertisingRate::all();
        return AdvertisingRateResource::collection($advertisingRates);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAdvertisingRateRequest $request)
    {
        $advertisingRate = AdvertisingRate::create($request->validated());
        return new AdvertisingRateResource($advertisingRate);
    }

    /**
     * Display the specified resource.
     */
    public function show(AdvertisingRate $advertisingRate)
    {
        return new AdvertisingRateResource($advertisingRate);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAdvertisingRateRequest $request, AdvertisingRate $advertisingRate)
    {
        $advertisingRate->update($request->validated());
        return new AdvertisingRateResource($advertisingRate);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AdvertisingRate $advertisingRate)
    {
        $advertisingRate->delete();
        return response()->noContent();
    }
}
