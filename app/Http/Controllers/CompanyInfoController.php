<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCompanyInfoRequest;
use App\Http\Requests\UpdateCompanyInfoRequest;
use App\Http\Resources\CompanyResource;
use App\Models\CompanyInfo;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;


class CompanyInfoController extends Controller
{
    //
    public function index()
    {
        return CompanyResource::collection(
            CompanyInfo::currentLang()
                ->get()
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCompanyInfoRequest $request)
    {
        //
        $image = $request->file('image')->store('images/company_infos');
        

        CompanyInfo::query()->create([
            'title' => $request->title,
            'description' => $request->input('description'),
            'image' => $image,
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
        return CompanyResource::collection(
            CompanyInfo::query()
                ->where('id', $id)
                ->get()
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCompanyInfoRequest $request, CompanyInfo $companyInfo)
    {
        //

        if ($request->hasFile('image')) {
            $image = $request->file('image')->store('images/company_infos');
            Storage::delete($companyInfo->image);
        } else {
            $image = $companyInfo->image;
        }

        $companyInfo->update([
            'title' => $request->title,
            'description' => $request->input('description'),
            'image' => $image,
            'language_id' => $request->input('language_id'),
        ]);

        return response()->json([
            'message' => 'Успешно обновлено',
        ]);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CompanyInfo $companyInfo)
    {
        Storage::delete($companyInfo->image);
        $companyInfo->delete();

        return response()->json([
            'message' => 'Успешно удалено',
        ]);
    }
}
