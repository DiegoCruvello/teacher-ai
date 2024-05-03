<?php

namespace TeacherAi\Integration\Application\Service;

use TeacherAi\Integration\Application\DTO\InputCreateImage;
use TeacherAi\Integration\Domain\Adapter\SendImageAdapterInterface;

class IntegrationService
{
    public function __construct(
      private readonly SendImageAdapterInterface $adapter,
    ) {
    }

    public function analyze(InputCreateImage $dto): array
    {
        return $this->adapter->analyzeImage($dto);
    }
}
