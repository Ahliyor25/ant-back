<?php

namespace App\Http\Controllers;

use App\Http\Bitrix\Deal\BitrixContact;
use App\Http\Requests\OrderCall\StoreOrderCallRequest;
use App\Http\Requests\OrderCall\UpdateOrderCallRequest;
use App\Http\Resources\OrderCallResource;
use App\Models\OrderCall;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Storage;


class OrderCallController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrderCallRequest $request)
    {
        OrderCall::query()->create([
            'name' => $request->name,
            'contact' => $request->contact,
            'comment' => $request->comment,
        ]);

        $data = [
            'chat_id' => env('CHAT_ID'),
            'parse_mode' => 'html',
            'text' => $this->generateMessage(
                $request->input('name'),
                $request->input('contact'),
                $request->comment,
            )
        ];

        $url = env('TELEGRAM_URL_API');

        $client = new Client(array('base_uri' => $url));
        $client->post('sendMessage', array('query' => $data));
        if ($request->hasFile('file')) {
            $this->sendFile($request->input('name'), $request->file('file'));
        }

        return response()->json([
            'message' => 'Успешно создано',
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(OrderCall $orderCall)
    {
        //

        return new OrderCallResource($orderCall);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrderCallRequest $request, OrderCall $orderCall)
    {
        //
        $orderCall->update([
            'name' => $request->name,
            'contact' => $request->contact,
            'comment' => $request->comment,
        ]);
        return response()->json([
            'message' => 'Успешно обновлено',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(OrderCall $orderCall)
    {
        //
        $orderCall->delete();

        return response()->json([
            'message' => 'Успешно удалено',
        ]);

    }



    /** Order call Request Telegram*/

    public function generateMessage($name, $contact, $comment)
    {
        $date = Carbon::now()->timezone('GMT+5')->locale('tj');
        $message = "<b>Заказать звонок!</b> \n";
        $message .= "<b>ФИО</b>: {$name}\n";
        $message .= "<b>Контакты</b>: {$contact}\n";
        $message .= "<b>Комментария</b>: {$comment}\n";
        $message .= "<b>Дата</b>: $date";
        return $message;
    }

    public function sendFile($name, $file)
    {
        $filename = $file->storeAs('files/vacancies', 'Resume.' . $file->getClientOriginalExtension());
        $file_pathname = Storage::path($filename);
        $arrayQuery = array(
            'chat_id' => env('CHAT_ID'),
            'caption' => $name,
            'document' => curl_file_create($file_pathname),
        );
        $ch = curl_init('https://api.telegram.org/bot' . env('TELEGRAM_TOKEN') . '/sendDocument');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $arrayQuery);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_exec($ch);
        curl_close($ch);
        Storage::delete($filename);
    }
}
