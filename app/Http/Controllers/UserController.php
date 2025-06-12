<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfileRequest;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Resources\OrderProfileResource;
use App\Http\Resources\UserResource;
use App\Models\Otp;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        return UserResource::collection(
            User::query()
                ->orderByDesc('id')
                ->filter($request)
                ->paginate($request->per_page)
        );
    }

    public function admins()
    {
        $roles = Role::query()
            ->whereIn('key', ['admin', 'manager'])
            ->pluck('id')
            ->toArray();
        return UserResource::collection(
            User::query()
                ->whereHas('roles', function ($q) use ($roles) {
                    $q->whereIn('roles.id', $roles);
                })
                ->get()
        );
    }

    public function store(StoreUserRequest $request)
    {
        $user = User::query()->create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'password' => Hash::make($request->input('password')),
        ]);
        $user->roles()->sync($request->input('role_ids'));
        return response()->json([
            'message' => 'Успешно создан',
            'user' => UserResource::make($user),
        ], 201);
    }

    public function update(UpdateProfileRequest $request)
    {
        $user = $request->user();
        $type = $request->has('email') ? 'email' : 'phone';
        if (!$request->input('otp') && $user->phone !== $request->input('phone')) {
            return response()->json([
                'message' => 'Код подтверждения является обязательным'
            ], 422);
        }
        if ($request->input('otp')) {
            $otp = Otp::query()
                ->where($type, $request->input($type))
                ->get()
                ->last();
            $error_message = new JsonResponse([
                'success' => false,
                'message' => 'Неправильный код подтверждения',
            ], 400);

            if (($otp == null) || ($otp->otp != $request->otp)) {
                return $error_message;
            }
            $otp->delete();
        }

        $user->update($request->only([
            'name',
            'phone',
            'address',
        ]));
        return response()->json([
            'message' => 'Успешно изменен!',
            'user' => $user,
        ]);
    }


    public function destroy(User $user)
    {
        $user->delete();

        return response()->json([
            'message' => 'Successfully deleted',
        ]);
    }

    public function updateUser(UpdateUserRequest $request, User $user)
    {
        $user->update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'password' => $request->has('password') ? Hash::make($request->input('password')) : $user->password,
        ]);
        $user->roles()->sync($request->role_ids);
        $user_id = $user->id;

        return response()->json([
            'message' => 'Успешно изменен',
        ]);
    }

    public function me()
    {
        return auth()->user() ?? abort(401);
    }

    public function adminMe()
    {
        $user = auth()->user();
        if (!$user) {
            abort(401);
        }
        return UserResource::make($user);
    }

    public function roles()
    {
        return Role::all();
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function meOrders(Request $request)
    {
        $user = auth()->user();
        return OrderProfileResource::collection(Order::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate($request->per_page));
    }

    public function genders(): \Illuminate\Http\JsonResponse
    {
        return response()->json(config('genders'));
    }

    public function call_api($url, $method, $params)
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
