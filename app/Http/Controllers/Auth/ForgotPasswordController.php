<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Otp;
use App\Models\User;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    public function resetPasswordWithPhone(Request $request)
    {
        $request->validate([
            'phone' => 'required|string|max:255',
            'password' => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required|string|min:6',
            'otp' => 'required|integer',
        ]);
        $otp = Otp::query()
            ->where('phone', $request->phone)
            ->get()
            ->last();

        if (($otp == null) || ($otp->otp != $request->otp)) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Неправильный код подтверждения',
            ], 400);
        }


        $user = User::query()->where('phone', $request->phone)->first();

        if (!$user) {
            return response()->json([
                'message' => 'Пользователь с таким номером телефона не найден'
            ]);
        }

        $user->update([
            'password' => Hash::make($request->input('password'))
        ]);
        $otp->delete();

        return response()->json([
            'message' => 'Пароль успешно обновлен!',
        ]);
    }

    public function resetPasswordWithEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required|string|min:6',
            'otp' => 'required|integer',
        ]);
        $otp = Otp::query()
            ->where('email', $request->email)
            ->get()
            ->last();

        if (($otp == null) || ($otp->otp != $request->otp)) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Неправильный код подтверждения',
            ], 400);
        }

        $user = User::query()->where('email', $request->email)->first();
        if (!$user) {
            return response()->json([
                'message' => 'Пользователь с таким email-ом не найден'
            ]);
        }
        $user->update([
            'password' => Hash::make($request->input('password'))
        ]);
        $otp->delete();
        return response()->json([
            'message' => 'Пароль успешно обновлен!',
        ]);
    }
}
