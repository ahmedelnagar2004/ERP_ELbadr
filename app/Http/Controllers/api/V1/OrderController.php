<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use App\Models\Order;
use App\Services\OrderService;
use App\Http\Requests\Api\V1\app\CheckoutRequest;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    use ApiResponse;

    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function checkout(CheckoutRequest $request)
    {
        try {
            $client = $request->user();
            
            $shippingData = [
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
            ];

            $order = $this->orderService->createOrder(
                $client,
                $shippingData,
                $request->payment_method
            );

            return $this->responseApi($order, 'تم إنشاء الطلب بنجاح');

        } catch (\Exception $e) {
            return $this->apiErrorMessage('حدث خطأ أثناء إنشاء الطلب: ' . $e->getMessage(), 500);
        }
    }


    public function index(Request $request)
    {
        $client = $request->user();
        $orders = Order::where('client_id', $client->id)
                       ->with(['items', 'shippingAddress'])
                       ->latest()
                       ->get();
                       
        return $this->responseApi(\App\Http\Resources\V1\OrderResource::collection($orders), 'تم استرجاع الطلبات بنجاح');
    }

    public function show(Request $request, $id)
    {
        $client = $request->user();
        $order = Order::where('client_id', $client->id)
                      ->where('id', $id)
                      ->with(['items', 'shippingAddress'])
                      ->first();

        if (!$order) {
            return $this->apiErrorMessage('الطلب غير موجود', 404);
        }

        return $this->responseApi(new \App\Http\Resources\V1\OrderResource($order), 'تم استرجاع تفاصيل الطلب بنجاح');
    }
}
