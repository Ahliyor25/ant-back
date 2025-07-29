<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AbonentController extends Controller
{
    //
    private $baseUrl = 'https://api.ant.tj:8009/api';

    public function mepassword(Request $request)
    {
        $response = Http::post("{$this->baseUrl}/abonent/mepassword", $request->all());
        return response()->json($response->json(), $response->status());
    }

    public function meauth(Request $request)
    {
        $response = Http::post("{$this->baseUrl}/abonent/meauth", $request->all());
        return response()->json($response->json(), $response->status());
    }

    public function cardadd(Request $request)
    {
        $response = Http::post("{$this->baseUrl}/abonent/cardadd", $request->all());
        return response()->json($response->json(), $response->status());
    }

    public function checkCardBalance(Request $request)
    {
        $card = $request->input('card');
        $key = "Qf438>uJizV.Fd!j9k6X";
        $signature = hash('sha256', $key . $card);

        $response = Http::post('https://api.ant.tj:811/cardbalance/get', [
            'card' => $card,
            'signature' => $signature,
        ]);

        return response()->json($response->json(), $response->status());
    }

    public function listCard(Request $request)
    {
        $response = Http::post("{$this->baseUrl}/abonent/listcard", $request->all());
        return response()->json($response->json(), $response->status());
    }

    public function tvChannelList(Request $request)
    {
        $response = Http::post("{$this->baseUrl}/abonent/tvchannellist", $request->all());
        return response()->json($response->json(), $response->status());
    }

    public function changeTarif(Request $request)
    {
        $response = Http::post("{$this->baseUrl}/abonent/changetarif", $request->all());
        return response()->json($response->json(), $response->status());
    }

    public function smsList(Request $request)
    {
        $response = Http::post("{$this->baseUrl}/abonent/smslist", $request->all());
        return response()->json($response->json(), $response->status());
    }

    public function historyList(Request $request)
    {
        $response = Http::post("{$this->baseUrl}/abonent/historylist", $request->all());
        return response()->json($response->json(), $response->status());
    }

    public function addZayavka(Request $request)
    {
        $response = Http::post("{$this->baseUrl}/zayavka/add", $request->all());
        return response()->json($response->json(), $response->status());
    }

    public function discountList(Request $request)
    {
        $response = Http::post("{$this->baseUrl}/abonent/discountlist", $request->all());
        return response()->json($response->json(), $response->status());
    }

    public function discount(Request $request)
    {
        $response = Http::post("{$this->baseUrl}/abonent/discount", $request->all());
        return response()->json($response->json(), $response->status());
    }

    public function callbackOrder(Request $request)
    {
        $response = Http::post('https://api.ant.tj:9154/callback/order', $request->all());
        return response()->json($response->json(), $response->status());
    }

    public function initAlifPayment(Request $request)
    {
        $payload = $request->only([
            'key',
            'token',
            'callback_url',
            'return_url',
            'amount',
            'order_id',
            'gate',
            'phone'
        ]);

        $headers = [
            'Content-Type' => 'application/json',
            'isMarketPlace' => 'false',
            'gate' => 'korti_milli',
        ];

        $response = Http::withHeaders($headers)
            ->post('https://test-web.alif.tj/v2/', $payload);

        return response()->json($response->json(), $response->status());
    }

    public function listTarifV2(Request $request)
    {
        $response = Http::post("{$this->baseUrl}/abonent/listtarifV2", $request->all());
        return response()->json($response->json(), $response->status());
    }
}
