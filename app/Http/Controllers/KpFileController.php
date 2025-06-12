<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\KpFileResource;
use App\Models\KpFile;
use Illuminate\Support\Facades\Storage;


class KpFileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return KpFileResource::collection(
            KpFile::all()
        );

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //

        $file = $request->file('file')->store('images/kp');

        KpFile::query()->create([
            'title' => $request->input('title'),
            'file' => $file,
        ]);

        return response()->json([
            'message' => 'successfully',
        ], 201);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, KpFile $kpFile)
    {
        //

        $data = [
            'title' => $request->input('title'),
        ];
    

        if ($request->hasFile('file')) {

            $newPath = $request->file('file')->store('images/kp');
    

            if ($kpFile->file) {
                Storage::delete($kpFile->file);
            }
    
            // Добавляем новый путь к файлу в массив обновлений
            $data['file'] = $newPath;
        }
    
        // Обновляем модель
        $kpFile->update($data);
    
        return response()->json([
            'message' => 'updated',
            'data' => $kpFile,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(KpFile $kpFile)
    {
        //

        if ($kpFile->file && \Storage::exists($kpFile->file)) {
            \Storage::delete($kpFile->file);
        }
    
        $kpFile->delete();
    
        return response()->json([
            'message' => 'deleted',
        ], 200);
    }
}
