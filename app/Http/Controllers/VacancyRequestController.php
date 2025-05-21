<?php

namespace App\Http\Controllers;

use App\Http\Requests\Vacancy\VacancyLeadRequest;
use App\Models\Vacancy;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use GuzzleHttp\Client;

class VacancyRequestController extends Controller
{
    public function sendMessage(VacancyLeadRequest $request)
    {
        $vacancy = Vacancy::query()->find($request->input('vacancy_id'));
        $data = [
            'chat_id' => env('CHAT_ID'),
            'parse_mode' => 'html',
            'text' => $this->generateMessage(
                $request->input('name'),
                $request->input('contact'),
                $vacancy->name,
            )
        ];

        $url = env('TELEGRAM_URL_API');

        $client = new Client(array('base_uri' => $url));
        $client->post('sendMessage', array('query' => $data));
        if ($request->hasFile('file')) {
            $this->sendFile($request->input('name'), $request->file('file'));
        }
        return response()->json([
            'message' => 'Успешно отправлено'
        ]);
    }

    public function generateMessage($name, $contact, $vacancyName)
    {
        $date = Carbon::now()->timezone('GMT+5')->locale('tj');
        $message = "<b>Завяка на вакансию</b> \n";
        $message .= "<b>Вакансия</b>: {$vacancyName}\n";
        $message .= "<b>ФИО</b>: {$name}\n";
        $message .= "<b>Контакты</b>: {$contact}\n";
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
