<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Auth\LoginClientRequest;
use App\Http\Requests\Api\V1\Auth\RegisterClientRequest;
use App\Http\Resources\V1\ClientResource;
use App\Http\Requests\Api\V1\Auth\UpdateClientProfileRequest;
use App\Models\Client;
use App\Enums\ClientStatus;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\welcomeMail;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;

class ClientAuthController extends Controller
{
    use ApiResponse;

    public function login(LoginClientRequest $request)
    {
        $client = Client::where('email', $request->email)->first();

        if (!$client || !Hash::check($request->password, $client->password)) {
            return $this->apiErrorMessage('البريد الالكتروني أو كلمة المرور غير صحيحة', 401);
        }

        if ($client->status !== ClientStatus::WEBSITE) {
            return $this->apiErrorMessage('الحساب غير مفعل', 403);
        }

        $token = $client->createToken('auth_token')->plainTextToken;

        return $this->responseApi([
             'user' => new ClientResource($client),
            'token' => $token
        ], 'تم تسجيل الدخول بنجاح');
    }

    public function register(RegisterClientRequest $request)
    {
        $client = Client::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'address' => $request->address,
            'status' => ClientStatus::WEBSITE,
            'password' => Hash::make($request->password),
            'balance' => 0,
        ]);


        $token = $client->createToken('auth_token')->plainTextToken;

        return $this->responseApi([
             'user' => new ClientResource($client),
            'token' => $token
        ], 'تم إنشاء الحساب بنجاح', true);
    }

    public function get_profile_client(Request $request)
    {
        return $this->responseApi(new ClientResource($request->user()), "Client profile retrieved successfully");
    }

    public function updateProfile(UpdateClientProfileRequest $request)
    {
        $client = $request->user();

        $client->update($request->validated());

        return $this->responseApi(new ClientResource($client), "تم تحديث الملف الشخصي بنجاح");
    }
}