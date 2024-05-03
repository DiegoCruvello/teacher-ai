<?php

namespace TeacherAi\Payment\Infrastructure\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use InvalidArgumentException;
use TeacherAi\Payment\Application\Service\ClientService;
use TeacherAi\Payment\Domain\Exception\ClientDomainException;
use TeacherAi\Payment\Domain\Exception\ClientNotFound;
use TeacherAi\Payment\Domain\Exception\CpfException;
use TeacherAi\Payment\Domain\ValueObject\CPF;
use TeacherAi\Payment\Infrastructure\Http\Requests\CreateClient;
use TeacherAi\Payment\Infrastructure\Resources\ClientResource;

class ClientController extends Controller
{
    public function __construct(
        public readonly ClientService $service,
    ) {
    }

    public function store(CreateClient $request): JsonResponse
    {
        try {
            $resp = $this->service->create($request->toDTO());
            return ClientResource::make($resp);
        } catch (ClientDomainException $e){
            return ClientResource::exception($e->getMessage(), $e->getCode());
        }
    }

    public function show(string $cpf): JsonResponse
    {
        try {
            $cpfValid = new CPF($cpf);
            $resp = $this->service->getClientByCpf((string)$cpfValid);
            return ClientResource::make($resp);
        } catch (CpfException $e){
            return ClientResource::exception($e->getMessage(), Response::HTTP_BAD_REQUEST);
        } catch (ClientNotFound $e){
            return ClientResource::exception($e->getMessage(), Response::HTTP_NOT_FOUND);
        }
    }
}
