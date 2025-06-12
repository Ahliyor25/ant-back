<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\Otp;
use App\Models\Role;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use App\Http\Bitrix\Contact\BitrixContact;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
       |--------------------------------------------------------------------------
       | Register Controller
       |--------------------------------------------------------------------------
       |
       | This controller handles the registration of new users as well as their
       | validation and creation. By default this controller uses a trait to
       | provide this functionality without requiring any additional code.
       |
       */
    use RegistersUsers;
    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = null;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }
    protected function create(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'] ?? null,
            'phone' => $data['phone'] ?? null,
            'address' => $data['address'] ?? null,
            'password' => Hash::make($data['password']),
        ]);
        $roleUser = Role::query()->where('key', 'user')->first();
        $user->roles()->sync([$roleUser->id]);

        return $user;
    }
    public function register(RegisterRequest $request)
    {
        if ($request->has('phone')) {
            $otp = Otp::query()
                ->where('phone', $request->input('phone'))
                ->get()
                ->last();
        } else {
            $otp = Otp::query()
                ->where('email', $request->input('email'))
                ->get()
                ->last();
        }
        $error_message = new JsonResponse([
            'success' => false,
            'message' => 'Неправильный код подтверждения',
        ], 400);
        if ($otp == null) {
            return $error_message;
        }
        if ($otp->otp != $request->input('otp')) {
            return $error_message;
        }
        $name = $request->input('name');
        $email = $request->input('email') ? $request->input('email') : '';
        $phone = $request->input('phone');
        $contact = new BitrixContact();
        $response = $contact->createContact($request);
        $contactId = $response->toArray()['result'];

        $user = User::query()->create(
            [
                'id' => $contactId,
                'name' => $request->input('name'),
                'email' => $request->input('email') ?? null,
                'phone' => $request->input('phone') ?? null,
                'address' => $request->input('address') ?? null,
                'password' => Hash::make($request->input('password')),
            ]
        );
        $token = $user->createToken('user')->plainTextToken;
        return new JsonResponse([
            'success' => true,
            'message' => 'Вы успешно зарегистрировались',
            'user' => $user,
            'token' => $token
        ], 201);
    }
}
