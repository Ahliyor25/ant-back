<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SupportAbonentFaq;

class SupportAbonentFaqController extends Controller
{
    //

    public function index(): \Illuminate\Http\JsonResponse
    {
        return response()->json(
            SupportAbonentFaq::currentLang()->get()
        );
    }

    public function store(Request $request)
    {
        $faq = SupportAbonentFaq::create($request->validate([
            'question' => 'required|string',
            'answer' => 'nullable|string',
            'language_id' => 'required|exists:languages,id',
        ]));

        return response()->json($faq, 201);
    }

    public function update(Request $request, SupportAbonentFaq $faq)
    {
        $faq->update($request->validate([
            'question' => 'required|string',
            'answer' => 'nullable|string',
            'language_id' => 'required|exists:languages,id',
        ]));

        return response()->json($faq);
    }

    public function destroy(SupportAbonentFaq $faq)
    {
        $faq->delete();
        return response()->json(null, 204);
    }

}
