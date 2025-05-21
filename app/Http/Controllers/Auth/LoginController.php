<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginAdminRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\UserResource;
use App\Models\Otp;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'phone' => 'required_if:email,null|string|max:255',
            'email' => 'required_if:phone,null|email',
            'otp' => 'required|integer|digits:4',
        ]);
        if ($request->input('email')) {
            $user = User::query()->where('email', $request->email)->first();
        } else {
            $user = User::query()->where('phone', $request->phone)->first();
        }

        if (!$user) {
            $message = 'Пользователь с таким номером телефона не существует';
            if ($request->input('email')) {
                $message = 'Пользователь с таким email-ом не существует';
            }
            return response()->json([
                'message' => $message,
            ], 404);
        }
        if ($request->input('email')) {
            $otp = Otp::query()
                ->where('email', $request->email)
                ->get()
                ->last();
        } else {
            $otp = Otp::query()
                ->where('phone', $request->phone)
                ->get()
                ->last();
        }

        if ($otp == null) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Неправильный код подтверждения',
            ], 400);
        }
        if ($otp->otp != $request->otp) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Неправильный код подтверждения',
            ], 400);
        }

        $otp->delete();

        $token = $user->createToken('webToken')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Успешно авторизовано',
            'user' => $user,
            'token' => $token,
        ]);
    }

    public function loginByPassword(LoginRequest $request)
    {
        $type = $request->input('email') ? 'email' : 'phone';
        $account = User::query()
            ->where($type, $request->input($type))
            ->first();
        if (!$account) {
            return $this->throwInvalidData();
        }

        if (!Auth::attempt([$type => $request->input($type), 'password' => $request->password])) {
            return $this->throwInvalidData();
        }

        $user = Auth::user();
        return response()->json([
            'message' => 'Успешно авторизовано',
            'token' => $user->createToken('user')->plainTextToken,
            'user' => $user,
        ]);
    }

    public function adminLogin(LoginAdminRequest $request)
    {
        $account = User::query()->where('email', $request->email)->first();
        if (!$account) {
            return $this->throwInvalidDataAdmin();
        }

        if (!Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return $this->throwInvalidDataAdmin();
        }
        if (!$account->isManager() && !$account->isAdmin()) {
            return response()->json([
                'message' => 'Доступ запрещен',
            ], 403);
        }

        $user = Auth::user();
        return response()->json([
            'message' => 'Успешно авторизовано',
            'token' => $user->createToken('admin')->plainTextToken,
            'user' => UserResource::make($user),
        ]);
    }

    public function logout(Request $request)
    {
        auth()->user()->currentAccessToken()->delete();
        return [
            'message' => 'Вы успешно вышли из системы'
        ];
    }

    public function throwInvalidData()
    {
        return response()->json([
            'message' => 'Неправильный номер телефона или пароль'
        ], 401);
    }

    public function throwInvalidDataAdmin()
    {
        return response()->json([
            'message' => 'Неправильные данные'
        ], 401);
    }
}
