<?php

namespace TeacherAi\Auth\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;

class AuthProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/../Http/AuthRoutes.php');
    }
}
