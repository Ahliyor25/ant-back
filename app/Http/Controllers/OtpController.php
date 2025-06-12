<?php

namespace App\Http\Controllers;

use App\Mail\OtpMail;
use App\Models\Otp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class OtpController extends Controller
{
    public function phoneVerify(Request $request)
    {
        $this->validate($request, [
            'phone' => 'required|string|max:255'
        ]);

        $dlm = ";";
        $otp = rand(1000, 9999);

        Otp::create([
            'otp' => $otp,
            'phone' => $request->phone,
            'expires_at' => now()->addMinutes(10)
        ]);
        $phone_number = $request->phone; //номер телефона
        $txn_id = uniqid(); //ID сообщения в вашей базе данных, оно должно быть уникальным для каждого сообщения
        $str_hash = hash('sha256', $txn_id . $dlm . env('SMS_LOGIN') . $dlm . env('SMS_SENDER') . $dlm . $phone_number . $dlm . env('SMS_HASH'));
        $message = "Ваш код: " . $otp;


        $params = array(
            "from" => env('SMS_SENDER'),
            "phone_number" => $phone_number,
            "msg" => $message,
            "str_hash" => $str_hash,
            "txn_id" => $txn_id,
            "login" => env('SMS_LOGIN'),
        );
        $result = $this->call_api(env('SMS_SERVER'), "GET", $params);
        if ((isset($result['error']) && $result['error'] == 0)) {
            $response = json_decode($result['msg']);
//            print_r($response);
            return response()->json([
                'success' => false,
                'message' => 'Произошла ошибка',
            ]);
            /* так выглядет ответ сервера
             * {
                    "status": "ok",
                    "timestamp": "2017-07-07 16:58:12",
                    "txn_id": "f890b43b964c2801f62b61a9662efff96dbaa82e007bc60c22ec41d9b22a3e0b",
                    "msg_id": 40127,
                    "smsc_msg_id": "45f22479",
                    "smsc_msg_status": "success",
                    "smsc_msg_parts": 1
                }
             */
            #echo "success: ".$response->msg_id; // id сообщения для проверки статуса сообщения в спорных случаях
        } else {
            return response()->json([
                'success' => true,
                'message' => 'Успешно отправлено',
            ]);
            #echo "error occured ".$result['msg'];
        }
    }

    public function emailVerify(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
        ]);

        $otp = rand(1000, 9999);

        Otp::create([
            'otp' => $otp,
            'email' => $request->email,
            'expires_at' => now()->addMinutes(10),
        ]);

        Mail::to($request->email)->send(new OtpMail([
            'otp' => $otp,
        ]));

        return response()->json([
            'message' => 'Сообщения успешно отправлено'
        ]);
    }

    public  function call_api($url, $method, $params)
    {
        $curl = curl_init();
        $data = http_build_query($params);
        if ($method == "GET") {
            curl_setopt($curl, CURLOPT_URL, "$url?$data");
        } else if ($method == "POST") {
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        } else if ($method == "PUT") {
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded', 'Content-Length:' . strlen($data)));
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        } else if ($method == "DELETE") {
            curl_setopt($curl, CURLOPT_URL, "$url?$data");
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
        } else {
            dd("unkonwn method");
        }
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $method
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);
        return $response;

    }
}
