<?php

namespace App\Traits;

use App\Http\Resources\V1\ItemResource;

trait ApiResponse
{
    public function responseApi($data = [], $message = "", $status = true)
    {
        return response()->json([
            'status' => $status,
            'data' => $data,
            'message' => $message,
        ]);
    }

    public function apiSuccessMessage($message)
    {
        return $this->responseApi([], $message, true);
    }

    public function apiErrorMessage($message, $statusCode = 400)
    {
        return $this->responseApi([], $message, false)->setStatusCode($statusCode);
    }

    // paginated collection response
    public function paginatedResponseApi($collection = [], $message = "")
    {
        return $this->responseApi([
            'items' => $this->resourceName::collection($collection),
            'pagination' => [
                'total' => $collection->total(),
                'count' => $collection->count(),
                'per_page' => $collection->perPage(),
                'current_page' => $collection->currentPage(),
                'total_pages' => $collection->lastPage()
            ]
        ]);
    }

    public function responseModelApi($model, $message = "", $status = 200)
    {
        return $this->responseApi(new $this->resourceName($model));
    }

}