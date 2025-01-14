<?php

namespace App\Http\Controllers\Api\Order;

use App\Enums\OrderEnums;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\V1\Order\OrderFilterRequest;
use App\Http\Requests\V1\Order\OrderRequest;
use App\Http\Resources\V1\Order\OrderCollection;
use App\Http\Resources\V1\Order\OrderResource;
use App\Repositories\CarRepositoryInterface;
use App\Repositories\OrderRepositoryInterface;
use App\Repositories\ServiceRepositoryInterface;
use Illuminate\Http\JsonResponse;

/**
 * Class OrdersV1Controller
 * @package App\Http\Controllers\Api\Order
 */
class OrdersV1Controller extends ApiController
{
    /**
     * @param CarRepositoryInterface $carRepository
     * @param ServiceRepositoryInterface $serviceRepository
     * @param OrderRepositoryInterface $orderRepository
     */
    public function __construct(
        protected CarRepositoryInterface     $carRepository,
        protected ServiceRepositoryInterface $serviceRepository,
        protected OrderRepositoryInterface   $orderRepository,
    )
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $orders = $this->orderRepository->getAll();

        return $this->success(message: __('Success'), data: new OrderCollection($orders));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param OrderRequest $request
     * @return JsonResponse
     */
    public function store(OrderRequest $request): JsonResponse
    {
        $user = $request->user();
        $service = $this->serviceRepository->getById($request->service_id);
        $car = $this->carRepository->getById($request->car_id);

        $order = $this->orderRepository->create([
            'barcode' => OrderEnums::PREFIX . trim(microtime()),
            'user_id' => $user->id,
            'service_id' => $service->id,
            'car_id' => $car->id,
            'status' => false,
            'price' => $service->price,
        ], $user);

        return $this->success(message: __('Success'), data: OrderResource::make($order));
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $order = $this->orderRepository->getById($id);

        return $this->success(message: __('Success'), data: new OrderResource($order));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param OrderRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(OrderRequest $request, int $id): JsonResponse
    {
        $car = $this->orderRepository->update($id, $request->validated());

        return $this->success(message: __('Success'), data: new OrderResource($car));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $this->carRepository->delete($id);

        return $this->success(
            message: __('Success'),
            data: [
                'message' => 'Deleted'
            ]
        );
    }

    /**
     * @param OrderFilterRequest $request
     * @return JsonResponse
     */
    public function filters(OrderFilterRequest $request): JsonResponse
    {
        $orders = $this->orderRepository->getUserOrdersByFilter($request);

        return $this->success(message: __('Success'), data: OrderResource::collection($orders));
    }
}
