<?php

namespace TeacherAi\Payment\Infrastructure\Providers;

use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;
use TeacherAi\Payment\Domain\Repository\ClientRepositoryInterface;
use TeacherAi\Payment\Domain\Repository\PaymentRepositoryInterface;
use TeacherAi\Payment\Infrastructure\Client\Asaas;
use TeacherAi\Payment\Infrastructure\Repository\ClientRepository;
use TeacherAi\Payment\Infrastructure\Repository\PaymentRepository;

class PaymentProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/../Http/PaymentRoutes.php');

        $this->app->bind(PaymentRepositoryInterface::class, PaymentRepository::class);
        $this->app->bind(ClientRepositoryInterface::class, ClientRepository::class);

        $this->app->bind(
            Asaas::class,
            fn() => new Asaas(
                new Client([
                    'base_uri' => config('services.client_asaas.base_uri'),
                    'headers' => [
                        'Accept' => 'application/json',
                        'Content-Type' => 'application/json',
                        'access_token' => config('services.client_asaas.api_key')
                    ]
                ])
            )
        );
    }
}
