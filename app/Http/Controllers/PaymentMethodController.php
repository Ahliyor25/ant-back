<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaymentMethod\StorePaymentMethodRequest;
use App\Http\Requests\PaymentMethod\UpdatePaymentMethodRequest;
use App\Http\Resources\PaymentMethodResource;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\Storage;

class PaymentMethodController extends Controller
{
    /**
     * Display a listing of the resource.
     */
//    public function index()
//    {
//        return PaymentMethodResource::collection(
//            PaymentMethod::query()->get()
//        );
//    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePaymentMethodRequest $request)
    {
        //
        $icon = $request->file("icon")->store("images/payment");
        PaymentMethod::create([
            "icon" => $icon,
            "name" => $request->input("name"),
            "key" => $request->key,
            "description" => $request->input("description"),
            "is_active" => $request->input("is_active"),
        ]);

        return response()->json([
            "message" => "Успешно создан"
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(PaymentMethod $paymentMethod)
    {
        //
        return new PaymentMethodResource($paymentMethod);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePaymentMethodRequest $request, PaymentMethod $paymentMethod)
    {
        if ($request->hasFile('icon')) {
            $icon = $request->file('icon')->store('images/payment');
            Storage::delete($paymentMethod->icon);
        } else {
            $icon = $paymentMethod->icon;
        }
        $paymentMethod->update([
            "name" => $request->input("name"),
            "key" => $request->key,
            "icon" => $icon,
            "description" => $request->input("description"),
            "is_active" => $request->input("is_active"),
        ]);

        return response()->json([
            "message" => "Успешно изменен"
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PaymentMethod $paymentMethod)
    {
        //
        Storage::delete($paymentMethod->icon);
        $paymentMethod->delete();
        return response()->json([
            "message" => "Успешно удален"
        ]);
    }
}
