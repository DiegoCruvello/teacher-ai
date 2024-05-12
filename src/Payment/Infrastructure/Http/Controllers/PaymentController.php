<?php

namespace TeacherAi\Payment\Infrastructure\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use TeacherAi\Payment\Application\Service\PaymentService;
use TeacherAi\Payment\Domain\Exception\CustomerException;
use TeacherAi\Payment\Domain\Exception\PaymentDomainException;
use TeacherAi\Payment\Domain\Exception\ReceivedException;
use TeacherAi\Payment\Domain\Exception\ReceivedNotFound;
use TeacherAi\Payment\Infrastructure\Http\Requests\ConfirmReceivedBoletoRequest;
use TeacherAi\Payment\Infrastructure\Http\Requests\CreateOrderRequest;
use TeacherAi\Payment\Infrastructure\Resources\PaymentResource;
use Throwable;

class PaymentController extends Controller
{
    public function __construct(
        public readonly PaymentService $service,
    ) {
    }

    public function createOrderBoleto(CreateOrderRequest $request): JsonResponse
    {
        try {
            $resp = $this->service->createOrderBoleto($request->toDTO());
            return PaymentResource::make($resp);
        } catch (CustomerException $e) {
            return PaymentResource::exception($e->getMessage(), Response::HTTP_NOT_FOUND);
        } catch (PaymentDomainException $e) {
            return PaymentResource::exception($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function createOrderPix(CreateOrderRequest $request): JsonResponse
    {
        $userId = auth()->user()->id;
        $lastOrder = Order::where('user_id', $userId)
            ->where('status', 'pending')
            ->latest()
            ->first();

        if($lastOrder){
            return PaymentResource::exception('Last order in status pending', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        try {
            $resp = $this->service->createOrderPix($request->toDTO());
            $order = new Order([
                'user_id' => $userId,
                'payment_id' => $resp->id,
                'status' => $resp->status,
                'invoiceUrl' => $resp->invoiceUrl,
            ]);
            $order->save();
            return PaymentResource::make($resp);
        } catch (CustomerException $e) {
            return PaymentResource::exception($e->getMessage(), Response::HTTP_NOT_FOUND);
        } catch (PaymentDomainException $e) {
            return PaymentResource::exception($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function createOrderCreditCard(CreateOrderRequest $request): JsonResponse
    {
        try {
            $resp = $this->service->createOrderCreditCard($request->toDTO());
            return PaymentResource::make($resp);
        } catch (CustomerException $e) {
            return PaymentResource::exception($e->getMessage(), Response::HTTP_NOT_FOUND);
        } catch (PaymentDomainException $e) {
            return PaymentResource::exception($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function confirmReceived(ConfirmReceivedBoletoRequest $request, string $id, int $plan): JsonResponse
    {
        $order = Order::where('payment_id', $id)->first();
        if (!$order) {
            return PaymentResource::exception('Order not found', Response::HTTP_NOT_FOUND);
        }
        try {
            $resp = $this->service->confirmReceived($request->toDTO(), $id);
            $order->status = $resp->status;
            $order->save();

            if ($resp->status === 'RECEIVED_IN_CASH') {
                $this->createSubscription($order, $plan);
            }

            return PaymentResource::make($resp);
        } catch (ReceivedException $e) {
            return PaymentResource::exception($e->getMessage(), Response::HTTP_BAD_REQUEST);
        } catch (ReceivedNotFound $e) {
            return PaymentResource::exception($e->getMessage(), Response::HTTP_NOT_FOUND);
        }
    }

    protected function createSubscription(Order $order, int $plan): void
    {
        $planModel = Plan::where('id', $plan)->first();

        $subscription = new Subscription([
            'user_id' => $order->user_id,
            'plan_id' => $plan,
            'current_usage' => $planModel->review_limit,
        ]);

        $subscription->save();
    }
}
