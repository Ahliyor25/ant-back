<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\RegionResource;
use App\Models\Region;


class RegionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return RegionResource::collection(
            Region::currentLang()
                  ->orderBy('order')
                  ->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $data = $request->validate([
            'language_id' => 'required|exists:languages,id',
            'name'        => 'required|string|max:255',
        ]);

        Region::create($data);

        return response()->json([
            'message' => 'Успешно создано',
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Region $region)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Region $region)
    {
        //
        $data = $request->validate([
            'language_id' => 'nullable|exists:languages,id',
            'name'        => 'nullable|string|max:255',
        ]);

        $region->update($data);
        return response()->json([
            'message' => 'Успешно обновлено'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Region $region)
    {
        //
        $region->delete();

        return response()->json([
            'message' => 'Успешно удалено'
        ]);
    }
}
