<?php

namespace TeacherAi\Payment\Domain\Repository;

use TeacherAi\Payment\Application\DTO\InputCreateClient;
use TeacherAi\Payment\Domain\Entity\Client;

interface ClientRepositoryInterface
{
    public function create(InputCreateClient $dto): Client;
    public function getClientByCpf(string $cpf): Client;
}
