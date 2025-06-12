<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ContactInfo;
use Illuminate\Http\JsonResponse;

class ContactInfoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        return response()->json(ContactInfo::first());
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'contact_phone' => 'required|string|max:30',
            'contact_email' => 'nullable|email',
            'youtube'       => 'nullable|url',
            'facebook'      => 'nullable|url',
            'instagram'     => 'nullable|url',
        ]);

        $info = ContactInfo::create($data);
        return response()->json($info, 201);
    }

    public function update(Request $request, ContactInfo $contactInfo): JsonResponse
    {
        $data = $request->validate([
            'contact_phone' => 'required|string|max:30',
            'contact_email' => 'required|email',
            'youtube'       => 'nullable|url',
            'facebook'      => 'nullable|url',
            'instagram'     => 'nullable|url'
        ]);

        $contactInfo->update($data);
        return response()->json($contactInfo);
    }

    public function destroy(ContactInfo $contactInfo): JsonResponse
    {
        $contactInfo->delete();
        return response()->json([], 204);
    }
}
