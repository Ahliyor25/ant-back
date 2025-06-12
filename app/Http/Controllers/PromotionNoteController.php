<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PromotionNotes;
use App\Http\Resources\PromotionNoteResource;
use App\Models\Promotion;

class PromotionNoteController extends Controller
{
    //

    public function index(Request $request)
    {

        $promotionId = $request->get('promotion_id');

        $query = PromotionNotes::query();

        if ($promotionId) {
            $query->where('promotion_id', $promotionId);
        }

        return PromotionNoteResource::collection($query->get());
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
        $promotionNode = PromotionNotes::create($request->all());
        return new PromotionNoteResource($promotionNode);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PromotionNotes $promotionNode)
    {
        $promotionNode = PromotionNotes::find($promotionNode->id);

        if (!$promotionNode) {
            return response()->json(['message' => 'Promotion condition not found'], 404);
        }

        $promotionNode->update($request->all());
        
        $promotionNode->refresh();

        $promotionNode->load('promotion');

        return new PromotionNoteResource($promotionNode);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PromotionNotes $promotionNode)
    {
        //

        $promotionNode->delete();
        return response()->json([
            'message' => 'Успешно удалено'
        ]);
        
    }
}
