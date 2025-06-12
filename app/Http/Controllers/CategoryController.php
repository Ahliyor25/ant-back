<?php
namespace App\Http\Controllers;
use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use App\Http\Resources\CategoryProvidersResource;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return CategoryResource::collection(
            Category::query()
                ->orderBy('order')
                ->get()
        );
    }

    public function providers(Category $category)
    {
        return CategoryProvidersResource::make($category);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
//        $icon = $request->file('icon')->store('images/categories');

        $category = Category::query()->create([
            'name' => $request->name,
            'description' => $request->input('description'),
            'icon' => 'default-product-image.svg',
            'order' => $request->input('order'),
        ]);

        $service_ids = $request->input('service_ids') ?? [];
        $category->services()->sync($service_ids);

        return response()->json([
            'message' => 'Успешно создано',
        ], 201);
    }
    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        return CategoryResource::make($category);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $category->update([
            'name' => $request->name,
            'description' => $request->input('description'),
            'icon' => 'default-product-image.svg',
            'order' => $request->input('order'),
        ]);

        $service_ids = $request->input('service_ids') ?? [];
        $category->services()->sync($service_ids);

        return response()->json([
            'message' => 'Успешно обновлено',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $category->delete();

        return response()->json([
            'message' => 'Успешно удалено',
        ]);
    }
}
