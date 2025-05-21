<?php

namespace App\Http\Controllers;

use App\Http\Requests\VacancyCategory\StoreVacancyCategoryRequest;
use App\Http\Requests\VacancyCategory\UpdateVacancyCategoryRequest;
use App\Http\Resources\VacancyCategoryCountResource;
use App\Http\Resources\VacancyCategoryResource;
use App\Models\Vacancy;
use App\Models\VacancyCategory;
use Illuminate\Http\Request;

class VacancyCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return response()->json([
            'data' => VacancyCategoryCountResource::collection(
                VacancyCategory::query()
                    ->filter($request)
                    ->get()
            ),
            'total_vacancies' => Vacancy::query()->count(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreVacancyCategoryRequest $request)
    {
        VacancyCategory::query()->create($request->validated());

        return response()->json([
            'message' => 'Успешно создано',
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(VacancyCategory $vacancyCategory)
    {
        return VacancyCategoryResource::make($vacancyCategory);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateVacancyCategoryRequest $request, VacancyCategory $vacancyCategory)
    {
        $vacancyCategory->update($request->validated());

        return response()->json([
            'message' => 'Успешно изменено',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(VacancyCategory $vacancyCategory)
    {
        $vacancyCategory->delete();

        return response()->json([
            'message' => 'Успешно удалено',
        ]);
    }
}
