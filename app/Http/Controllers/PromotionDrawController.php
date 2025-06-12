<?php

namespace App\Http\Controllers;

use App\Models\PromotionDraw;
use Illuminate\Http\Request;
use App\Http\Resources\PromotionDrawResource;

class PromotionDrawController extends Controller
{
    //
    public function index(Request $request)
    {
        //
        $promotionId = $request->get('promotion_id');

        $query = PromotionDraw::query();

        if ($promotionId) {
            $query->where('promotion_id', $promotionId);
        }

        return PromotionDrawResource::collection($query->get());        

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

        $promotionDraw = PromotionDraw::create($request->all());

        return new PromotionDrawResource($promotionDraw);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PromotionDraw $promotionDraw)
    {
        $promotionDraw = PromotionDraw::find($promotionDraw->id);

        if (!$promotionDraw) {
            return response()->json(['message' => 'Promotion condition not found'], 404);
        }

        $promotionDraw->update($request->all());
        
        $promotionDraw->refresh();

        $promotionDraw->load('promotion');

        return new PromotionDrawResource($promotionDraw);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PromotionDraw $promotionDraw)
    {
        //
        $promotionDraw->delete();
        return response()->json([
            'message' => 'Успешно удалено'
        ]);
        
    }
}
