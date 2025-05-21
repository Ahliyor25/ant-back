<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserUpdatePasswordRequest;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Support\Facades\Hash;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */

    public function changePassword(UserUpdatePasswordRequest $request)
    {
        $user = auth()->user();

        if (!Hash::check($request->input('old_password'), $user->password)) {
            return response()->json([
                'message' => 'Неправильный пароль'
            ], 401);
        }

        $user->update([
            'password' => Hash::make($request->input('password'))
        ]);

        return response()->json([
            'message' => 'Пароль успешно обновлен!',
        ]);
    }
}
