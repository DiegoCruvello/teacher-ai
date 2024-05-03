<?php

namespace TeacherAi\Payment\Application\Service;

use TeacherAi\Payment\Application\DTO\InputCreateClient;
use TeacherAi\Payment\Domain\Entity\Client;
use TeacherAi\Payment\Domain\Repository\ClientRepositoryInterface;

class ClientService
{
    public function __construct(
        public readonly ClientRepositoryInterface $repository
    ) {
    }

    public function create(InputCreateClient $dto): Client
    {
        return $this->repository->create($dto);
    }

    public function getClientByCpf(string $cpf): Client
    {
        return $this->repository->getClientByCpf($cpf);
    }
}
