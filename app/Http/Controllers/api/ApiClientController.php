<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Http\Requests\Api\RegisterClientRequest;
use App\Http\Requests\Api\LoginClientRequest;
use App\Enums\ClientStatus;
use Illuminate\Support\Facades\Hash;
use App\Mail\welcomeMail;
use Illuminate\Support\Facades\Mail;
class ApiClientController extends Controller
{

    public function index()
    { 
        $clients = Client::all()->where('status', ClientStatus::WEBSITE);
        return response()->json($clients);
    }

    public function login(LoginClientRequest $request)
    {
        $client = Client::where('email', $request->email)->first();
        
        return response()->json($client);
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
        Mail::to($client->email)->send(new welcomeMail($client));
        
        return response()->json([
            'success' => true,
            'message' => 'تم إنشاء الحساب بنجاح',
            'data' => $client
        ], 201);
    }

    public function profile($id)
    {
        $client = Client::where('id', $id)
                      ->where('status', ClientStatus::WEBSITE)
                      ->firstOrFail();
                      
        return response()->json([
            'success' => true,
            'data' => $client
        ]);
    }
}
