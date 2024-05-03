<?php

namespace TeacherAi\Integration\Infrastructure\Providers;

use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;
use TeacherAi\Integration\Domain\Adapter\SendImageAdapterInterface;
use TeacherAi\Integration\Infrastructure\Adapter\OpenAiAdapter;
use TeacherAi\Integration\Infrastructure\Client\OpenAi;

class IntegrationProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/../Http/IntegrationRoutes.php');

        $this->app->bind(SendImageAdapterInterface::class, OpenAiAdapter::class);

        $this->app->bind(OpenAi::class, fn () => new OpenAi(
            new Client([
                'base_uri' => config('services.openai.base_uri'),
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . config('services.openai.authorization'),
                ],
            ]),
        ));
    }
}
