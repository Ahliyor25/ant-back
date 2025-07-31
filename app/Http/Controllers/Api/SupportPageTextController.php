<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SupportPageText;

class SupportPageTextController extends Controller
{
    //
    public function show()
    {
        return response()->json(
            SupportPageText::currentLang()->firstOrFail()
        );
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string',
            'description' => 'nullable|string',
            'language_id' => 'required|exists:languages,id',
        ]);

        $text = SupportPageText::firstOrFail();
        $text->update($data);

        return response()->json($text);
    }
}
