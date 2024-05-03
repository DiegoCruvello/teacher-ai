<?php

namespace TeacherAi\Integration\Domain\Adapter;

use TeacherAi\Integration\Application\DTO\InputCreateImage;

interface SendImageAdapterInterface
{
    public function analyzeImage(InputCreateImage $dto): array;
}
