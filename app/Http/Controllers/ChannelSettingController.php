<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChannelSetting;
use App\Http\Resources\ChannelSettingResource;

use Illuminate\Support\Facades\Storage;

class ChannelSettingController extends Controller
{
    //

    public function index()
    {
        return ChannelSettingResource::collection(
            ChannelSetting::currentLang()
                ->get()
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $request->validate([
            'title' => 'required|string|max:255',
            'file' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx',
        ]);

        //
        $file = $request->file('file')->store('files/channel_settings');
        

        ChannelSetting::query()->create([
            'title' => $request->title,
            'file' => $file,
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
        return ChannelSettingResource::collection(
            ChannelSetting::query()
                ->where('id', $id)
                ->get()
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ChannelSetting $channelSetting)
    {
        //

        if ($request->hasFile('file')) {
            $file = $request->file('file')->store('files/channel_settings');
            Storage::delete($channelSetting->file);
        } else {
            $file = $channelSetting->file;
        }

        $channelSetting->update([
            'title' => $request->title,
            'file' => $file,
            'language_id' => $request->input('language_id'),
        ]);

        return response()->json([
            'message' => 'Успешно обновлено',
        ]);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ChannelSetting $channelSetting)
    {
        Storage::delete($channelSetting->file);
        $channelSetting->delete();

        return response()->json([
            'message' => 'Успешно удалено',
        ]);
    }
}
