<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Statistic;

class StatisticController extends Controller
{

    public function index()
    {
        return [
            'data' => Statistic::currentLang()->get(),
        ];
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'value' => 'required|string',
            'description' => 'required|string',
            'language_id' => 'required|exists:languages,id',
        ]);

        return Statistic::create($data);
    }

    public function update(Request $request, Statistic $statistic)
    {
        $data = $request->validate([
            'value' => 'sometimes|string',
            'description' => 'sometimes|string',
            'language_id' => 'sometimes|exists:languages,id',
        ]);

        $statistic->update($data);
        return $statistic;
    }

    public function destroy(Statistic $statistic)
    {
        $statistic->delete();
        return response()->noContent();
    }
    
}
