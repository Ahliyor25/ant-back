<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PackageAttribute;

use App\Http\Requests\PackageAttr\StorePackageAttr;
use App\Http\Requests\PackageAttr\UpdatePackageAttr;

use App\Http\Resources\PackageAttrResource;
use App\Models\Package;
use Illuminate\Support\Facades\Storage;


class PackageAttrController extends Controller
{
    //

    public function index(Package $package)
    {
        $packageAttributes = PackageAttribute::where('package_id', $package->id)->get();
        return PackageAttrResource::collection($packageAttributes);
    }

    public function store(StorePackageAttr $request){

        $image = $request->file('icon')->store('images/package_attributes');

        PackageAttribute::create([
            'package_id' => $request->input('package_id'),
            'icon' => $image,
            'text' => $request->input('text'),
            'order' => $request->input('order'),
        ]);

        return response()->json([
            'message' => 'Package attribute created successfully',
        ], 201);
    }

    public function update(UpdatePackageAttr $request, PackageAttribute $packageAttribute )
    {
        $icon = $packageAttribute->icon;
        if ($request->hasFile('icon')) {
            $icon = $request->file('icon')->store('images/package_attributes');
            Storage::delete($packageAttribute->icon);
        }
    
        $packageAttribute->update([
            'package_id' => $request->input('package_id'),
            'icon' => $icon,
            'text' => $request->input('text'),
            'order' => $request->input('order', 0),
        ]);
    
        return response()->json([
            'message' => 'updated successfully',
            'data' => new PackageAttrResource($packageAttribute),
        ]);

    }

    public function destroy(PackageAttribute $packageAttribute)
    {
        if ($packageAttribute->icon && \Storage::exists($packageAttribute->icon)) {
            \Storage::delete($packageAttribute->icon);
        }
    
        $packageAttribute->delete();
    
        return response()->json([
            'message' => 'deleted',
        ], 200);
    }

}
