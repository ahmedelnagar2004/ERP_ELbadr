<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LoginClientRequest;
use App\Http\Requests\Api\RegisterClientRequest;
use App\Http\Resources\V1\ClientResource;
use App\Models\Client;
use App\Enums\ClientStatus;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\welcomeMail;
use Illuminate\Http\Request;

class ClientAuthController extends Controller
{
    public function login(LoginClientRequest $request)
    {
        $client = Client::where('email', $request->email)->first();

        if (!$client || !Hash::check($request->password, $client->password)) {
            return response()->json([
                'success' => false,
                'message' => 'البريد الالكتروني أو كلمة المرور غير صحيحة'
            ], 401);
        }

        if ($client->status !== ClientStatus::WEBSITE) {
            return response()->json([
                'success' => false,
                'message' => 'الحساب غير مفعل'
            ], 403);
        }

        $token = $client->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'تم تسجيل الدخول بنجاح',
            'data' => new ClientResource($client),
            'token' => $token
        ]);
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

        return response()->json([
            'success' => true,
            'message' => 'تم إنشاء الحساب بنجاح',
            'data' => new ClientResource($client),
            'token' => $token
        ], 201);
    }
}
