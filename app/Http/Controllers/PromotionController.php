<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Resources\PromotionResource;
use App\Http\Requests\Promotion\StorePromotionRequest;
use App\Http\Requests\Promotion\UpdatePromotionRequest;
use App\Models\Promotion;
use Illuminate\Support\Facades\Storage;

class PromotionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Promotion::currentLang();
    
        if ($request->has('type')) {
            $query->where('type', $request->type); // например: ?type=news
        }
    
        $promotions = $query->orderBy('order')
                            ->paginate($request->per_page);
    
        return PromotionResource::collection($promotions);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePromotionRequest $request)
    {
        //
        $image = $request->file('image')->store('images/promotion');

        Promotion::query()->create([
            'title'         =>  $request->input('title'),
            'description'   =>  $request->input('description'),
            'image'         =>  $image,
            'published_at'  =>  $request->input('published_at'),
            'type'          =>  $request->input('type'),
            'order'         => $request->input('order'),
            'language_id'   => $request->input('language_id'),
        ]);

        return response()->json([
            'message' => 'Успешно создано',
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Promotion $promotion)
    {
        //
        return new PromotionResource($promotion);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePromotionRequest $request, Promotion $promotion)
    {

        $image = $promotion->image;
        if ($request->hasFile('image')) {
            $image = $request->file('image')->store('images/promotion');
            Storage::delete($promotion->image);
        }
        $promotion->update([
            'title'             => $request->input('title'),
            'description'       => $request->input('description'),
            'image'             => $image,
            'published_at'      => $request->input('published_at'),
            'description'       => $request->input('description'),
        ]);

        return response()->json([
            'message' => 'Успешно изменен'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Promotion $promotion)
    {
        //

        Storage::delete($promotion->image);

        $promotion->delete();

        return response()->json([
            'message' => 'Успешно удален',
        ]);
        
    }
}
