<?php

namespace App\Http\Controllers;

use App\Http\Requests\Vacancy\StoreVacancyRequest;
use App\Http\Requests\Vacancy\UpdateVacancyRequest;
use App\Http\Resources\VacancyResource;
use App\Models\Vacancy;
use Illuminate\Http\Request;

class VacancyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return VacancyResource::collection(
            Vacancy::query()
                ->orderByDesc('id')
                ->filter($request)
                ->paginate($request->per_page)
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreVacancyRequest $request)
    {
        $vacancy = Vacancy::query()->create($request->validated());

        return response()->json([
            'message' => 'Успешно создано',
            'data' => VacancyResource::make($vacancy),
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Vacancy $vacancy)
    {
        return VacancyResource::make($vacancy);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateVacancyRequest $request, Vacancy $vacancy)
    {
        $vacancy->update([
            'name' => $request->input('name'),
            'short_description' => $request->input('short_description'),
            'description' => $request->input('description'),
            'vacancy_category_id' => $request->input('vacancy_category_id'),
        ]);

        return response()->json([
            'message' => 'Успешно изменено',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vacancy $vacancy)
    {
        $vacancy->delete();

        return response()->json([
            'message' => 'Успешно удалено',
        ]);
    }
}
