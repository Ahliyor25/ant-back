<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Package;
use App\Http\Resources\PackageResource;
use App\Http\Requests\Package\StorePackageRequest;
use App\Http\Requests\Package\UpdatePackageRequest;

class PackageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return PackageResource::collection(
            Package::currentLang()->orderBy('order')
            ->type($request->type, 'standard')
            ->paginate($request->input('per_page', 10))
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePackageRequest  $request)
    {
        //
        $image = $request->file('image')->store('images/packages');

        Package::query()->create([
            'name' => $request->name,
            'description' => $request->input('description'),
            'image' => $image,
            'type_connection' => $request->input('type_connection', 'on_connect'),
            'type' => $request->input('type', 'standard'),
            'discount' => $request->input('discount', 0),
            'monthly_price' => $request->input('monthly_price'),
            'yearly_price' => $request->input('yearly_price'),
            'order' => $request->input('order'),
            'language_id' => $request->input('language_id'),
        ]);

        return response()->json([
            'message' => 'Успешно создано',
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Package $package)
    {
        //
        return new PackageResource($package);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePackageRequest $request, Package $package)
    {
        //

        $package->update($request->validated());
        return new PackageResource($package);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Package $package)
    {
        //
        $package->delete();
        return response()->json([
            'message' => 'Package deleted successfully'
        ], 200);
    }
}
