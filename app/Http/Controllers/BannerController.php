<?php

namespace App\Http\Controllers;

use App\Http\Requests\Banner\StoreBannerRequest;
use App\Http\Requests\Banner\UpdateBannerRequest;
use App\Http\Resources\BannerResource;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
    return BannerResource::collection(
        Banner::currentLang()
              ->orderBy('order')
              ->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBannerRequest $request)
    {
        //
        $image = $request->file('image')->store('images/banners');
        

        Banner::query()->create([
            'title' => $request->title,
            'description' => $request->input('description'),
            'image' => $image,
            'button_text' => $request->input('button_text'),
            'link' => $request->input('link'),
            'order' => $request->input('order'),
            'type' => $request->input('type'),
            'language_id' => $request->input('language_id'),
        ]);

        return response()->json([
            'message' => 'Успешно создано',
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $type)
    {
        return BannerResource::collection(
            Banner::query()
                ->where('type', $type)
                ->orderBy('order')
                ->get()
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBannerRequest $request, Banner $banner)
    {
        //

        if ($request->hasFile('image')) {
            $image = $request->file('image')->store('images/banners');
            Storage::delete($banner->image);
        } else {
            $image = $banner->image;
        }

        $banner->update([
            'title' => $request->title,
            'description' => $request->input('description'),
            'image' => $image,
            'button_text' => $request->input('button_text'),
            'link' => $request->input('link'),
            'order' => $request->input('order'),
            'type' => $request->input('type'),
            'language_id' => $request->input('language_id'),
        ]);

        return response()->json([
            'message' => 'Успешно обновлено',
        ]);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Banner $banner)
    {
        Storage::delete($banner->image);
        $banner->delete();

        return response()->json([
            'message' => 'Успешно удалено',
        ]);
    }
}
