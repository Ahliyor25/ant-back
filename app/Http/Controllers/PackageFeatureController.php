<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\PackageFeature;

use App\Http\Requests\StorePackageFeature;
use App\Http\Requests\UpdatePackageFeature;

use App\Http\Resources\PackageFeautureResource;
use App\Models\Package;
use Illuminate\Support\Facades\Storage;



class PackageFeatureController extends Controller
{
    //
    public function index(Package $package)
    {
        $packageAttributes = PackageFeature::where('package_id', $package->id)->get();
        return PackageFeautureResource::collection($packageAttributes);
    }

    public function store(StorePackageFeature $request){

        $image = $request->file('icon')->store('images/package_features');

        PackageFeature::create([
            'package_id' => $request->input('package_id'),
            'icon' => $image,
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'order' => $request->input('order'),
        ]);

        return response()->json([
            'message' => 'Package feauture created successfully',
        ], 201);
    }

    public function update(UpdatePackageFeature $request, PackageFeature $packageFeature )
    {
        $icon = $packageFeature->icon;
        if ($request->hasFile('icon')) {
            $icon = $request->file('icon')->store('images/package_features');
            Storage::delete($packageFeature->icon);
        }
    
        $packageFeature->update([
            'package_id' => $request->input('package_id'),
            'icon' => $icon,
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'order' => $request->input('order', 0),
        ]);
    
        return response()->json([
            'message' => 'updated successfully',
            'data' => new PackageFeautureResource($packageFeature),
        ]);

    }

    public function destroy(PackageFeature $packageFeature)
    {
        if ($packageFeature->icon && \Storage::exists($packageFeature->icon)) {
            \Storage::delete($packageFeature->icon);
        }
    
        $packageFeature->delete();
    
        return response()->json([
            'message' => 'deleted',
        ], 200);
    }
}
