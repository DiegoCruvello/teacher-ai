<?php

namespace TeacherAi\Integration\Application\Service;

use App\Models\Subscription;
use TeacherAi\Integration\Application\DTO\InputCreateImage;
use TeacherAi\Integration\Domain\Adapter\SendImageAdapterInterface;

class IntegrationService
{
    public function __construct(
        private readonly SendImageAdapterInterface $adapter,
        private readonly Subscription $model
    ) {
    }

    public function analyze(InputCreateImage $dto, int $id): array
    {
        $usage = $this->model->where('user_id', $id)->first();

        if ($usage && $usage->current_usage > 0) {
            $data = $this->adapter->analyzeImage($dto);
            $usage->current_usage -= 1;
            $usage->save();
            return $data;
        }

        return [];
    }

    public function getLimitUsage(int $id): int
    {
        return $this->model->where('id', $id)->first()->current_usage;
    }
}
