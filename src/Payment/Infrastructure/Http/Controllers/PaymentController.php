<?php

namespace TeacherAi\Payment\Infrastructure\Http\Controllers;

use App\Http\Controllers\Controller;
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
        try {
            $resp = $this->service->createOrderPix($request->toDTO());
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

    public function confirmReceived(ConfirmReceivedBoletoRequest $request, string $id): JsonResponse
    {
        try {
            $resp = $this->service->confirmReceived($request->toDTO(), $id);
            return PaymentResource::make($resp);
        } catch (ReceivedException $e) {
            return PaymentResource::exception($e->getMessage(), Response::HTTP_BAD_REQUEST);
        } catch (ReceivedNotFound $e) {
            return PaymentResource::exception($e->getMessage(), Response::HTTP_NOT_FOUND);
        }
    }
}
