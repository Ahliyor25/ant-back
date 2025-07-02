<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Advantage;
use App\Http\Resources\AdvantageResource;
use App\Http\Requests\StoreAdvantageRequest;
use App\Http\Requests\UpdateAdvantageRequest;
use Illuminate\Support\Facades\Storage;


class AdvantageController extends Controller
{
    //

    public function index()
    {
        return AdvantageResource::collection(
            Advantage::currentLang()
                ->get()
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAdvantageRequest $request)
    {
        //
        $icon = $request->file('icon')->store('images/advantages');

        Advantage::query()->create([
            'title' => $request->title,
            'description' => $request->input('description'),
            'icon' => $icon,
            'language_id' => $request->input('language_id'),
        ]);

        return response()->json([
            'message' => 'Успешно создано',
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return AdvantageResource::collection(
            Advantage::query()
                ->where('id', $id)
                ->get()
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAdvantageRequest $request, Advantage $advantage)
    {
        //

        if ($request->hasFile('icon')) {
            $icon = $request->file('icon')->store('images/advantages');
            Storage::delete($advantage->icon);
        } else {
            $icon = $advantage->icon;
        }

        $advantage->update([
            'title' => $request->title,
            'description' => $request->input('description'),
            'icon' => $icon,
            'language_id' => $request->input('language_id'),
        ]);

        return response()->json([
            'message' => 'Успешно обновлено',
        ]);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Advantage $advantage)
    {
        Storage::delete($advantage->icon);
        $advantage->delete();

        return response()->json([
            'message' => 'Успешно удалено',
        ]);
    }
}
