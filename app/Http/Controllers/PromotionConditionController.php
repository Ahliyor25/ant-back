<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PromotionCondition;
use App\Http\Resources\PromotionConditionResource;
use App\Models\Promotion;

class PromotionConditionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $promotionId = $request->get('promotion_id');

        $query = PromotionCondition::query();

        if ($promotionId) {
            $query->where('promotion_id', $promotionId);
        }

        return PromotionConditionResource::collection($query->get());

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'promotion_id' => 'required|exists:promotions,id',
            'content' => 'required|string',
        ]);
        $promotionCondition = PromotionCondition::create($request->all());
        return new PromotionConditionResource($promotionCondition);
    }

    /**
     * Display the specified resource.
     */
    public function show(PromotionCondition $promotionCondition)
    {


    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PromotionCondition $promotionCondition)
    {
        $promotionCondition = PromotionCondition::find($promotionCondition->id);

        if (!$promotionCondition) {
            return response()->json(['message' => 'Promotion condition not found'], 404);
        }

        $promotionCondition->update($request->all());
        
        $promotionCondition->refresh();

        $promotionCondition->load('promotion');

        return new PromotionConditionResource($promotionCondition);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PromotionCondition $promotionCondition)
    {
        //

        $promotionCondition->delete();
        return response()->json([
            'message' => 'Успешно удалено'
        ]);
        
    }
}
