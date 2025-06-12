<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\PromotionWinner;

use App\Http\Requests\PromotionWinner\StorePromotionWinnerRequest;
use App\Http\Requests\PromotionWinner\UpdatePromotionWinnerRequest;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\PromotionWinnerResource;

class PromotionWinnerController extends Controller
{
    //
    public function index(Request $request)
    {

        $query = PromotionWinner::query()->with(['promotion', 'promotionDraw', 'promotionPrize', 'region']);

        if ($request->has('promotion_id')) {
            $query->where('promotion_id', $request->promotion_id);
        }
    
        $winners = $query->latest()->paginate(30);
    
        return PromotionWinnerResource::collection($winners);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePromotionWinnerRequest $request)
    {
        //
        $image = $request->file('image')->store('images/promotionwinner');

        PromotionWinner::query()->create([
            'image'         =>  $image,
            'promotion_id'         =>  $request->input('promotion_id'),
            'promotion_draw_id'   =>  $request->input('promotion_draw_id'),
            'promotion_prize_id'  =>  $request->input('promotion_prize_id'),
            'region_id'          =>  $request->input('region_id'),
            'full_name'         => $request->input('full_name'),
            'month'   => $request->input('month'),
            'prize_type'   => $request->input('prize_type')
        ]);

        return response()->json([
            'message' => 'Успешно создано',
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(PromotionWinner $promotionWinner)
    {
        //
        return new PromotionWinnerResource($promotionWinner);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePromotionWinnerRequest $request, PromotionWinner $promotionWinner)
    {

        $image = $promotionWinner->image;
        if ($request->hasFile('image')) {
            $image = $request->file('image')->store('images/promotionwinner');
            Storage::delete($promotionWinner->image);
        }
        $promotionWinner->update([
            'image'         =>  $image,
            'promotion_id'         =>  $request->input('promotion_id'),
            'promotion_draw_id'   =>  $request->input('promotion_draw_id'),
            'promotion_prize_id'  =>  $request->input('promotion_prize_id'),
            'region_id'          =>  $request->input('region_id'),
            'full_name'         => $request->input('full_name'),
            'month'   => $request->input('month'),
            'prize_type'   => $request->input('prize_type')
        ]);

        return response()->json([
            'message' => 'Успешно изменен'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PromotionWinner $promotionWinner)
    {
        //

        Storage::delete($promotionWinner->image);

        $promotionWinner->delete();

        return response()->json([
            'message' => 'Успешно удален',
        ]);
        
    }
}
