<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Agreement;
use Illuminate\Support\Facades\Storage;

class AgreementController extends Controller
{
    //

    public function index()
    {
        $agreement = Agreement::currentLang()->firstOrFail();
            if ($agreement->file) {
                $agreement->file = url('storage/' . $agreement->file);
            }
        return $agreement;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'file' => 'required|file|mimes:pdf,doc,docx|max:20480',
            'language_id' => 'required|exists:languages,id',
        ]);

        $file = $request->file('file')->store('files/agreements');

        Agreement::query()->create([
            'file' => $file,
            'language_id' => $request->input('language_id'),
        ]);

        return response()->json([
            'message' => 'successfully',
        ], 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        //
        $data = $request->validate([
            'language_id' => 'nullable|exists:languages,id',
        ]);

        $agreement = Agreement::currentLang()->firstOrFail();

        if ($request->hasFile('file')) {

            $newPath = $request->file('file')->store('files/agreements');

            if ($agreement->file) {
                Storage::delete($agreement->file);
            }

            $data['file'] = $newPath;
        }

        $agreement->update($data);

        return response()->json([
            'message' => 'updated',
            'data' => $agreement,
        ]);
    }
}
