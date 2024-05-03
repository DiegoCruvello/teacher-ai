<?php

namespace TeacherAi\Integration\Infrastructure\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
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
        $data = $this->service->analyze($request->toDTO());
        return new JsonResponse($data, Response::HTTP_OK);
    }
}
