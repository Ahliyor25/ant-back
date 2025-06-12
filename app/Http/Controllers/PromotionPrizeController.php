<?php

namespace App\Http\Controllers;

use App\Models\PromotionPrize;
use Illuminate\Http\Request;
use App\Http\Resources\PromotionPrizeResource;


class PromotionPrizeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $promotionId = $request->get('promotion_id');

        $query = PromotionPrize::query();

        if ($promotionId) {
            $query->where('promotion_id', $promotionId);
        }

        return PromotionPrizeResource::collection($query->get());        

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'promotion_id' => 'required|exists:promotions,id',
            'title' => 'required|string',
            'description' => 'nullable|string',
        ]);

        $promotionPrize = PromotionPrize::create($request->all());

        return new PromotionPrizeResource($promotionPrize);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PromotionPrize $promotionPrize)
    {
        $promotionPrize = PromotionPrize::find($promotionPrize->id);

        if (!$promotionPrize) {
            return response()->json(['message' => 'Promotion condition not found'], 404);
        }

        $promotionPrize->update($request->all());
        
        $promotionPrize->refresh();

        $promotionPrize->load('promotion');

        return new PromotionPrizeResource($promotionPrize);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PromotionPrize $promotionPrize)
    {
        //
        
        $promotionPrize->delete();
        return response()->json([
            'message' => 'Успешно удалено'
        ]);
        
    }
}
