<?php

namespace TeacherAi\Integration\Infrastructure\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use TeacherAi\Integration\Application\Service\IntegrationService;
use TeacherAi\Integration\Infrastructure\Http\Requests\CreateImageRequest;

class IntegrationController
{
    public function __construct(
        public readonly IntegrationService $service
    ) {
    }

    public function image(CreateImageRequest $request): JsonResponse
    {
        $data = $this->service->analyze($request->toDTO(), Auth::user()->id);
        return new JsonResponse($data, Response::HTTP_OK);
    }

    public function getLimitUsage(): JsonResponse
    {
        $quantity = $this->service->getLimitUsage(Auth::user()->id);
        return new JsonResponse(['usage' => $quantity], Response::HTTP_OK);
    }
}
