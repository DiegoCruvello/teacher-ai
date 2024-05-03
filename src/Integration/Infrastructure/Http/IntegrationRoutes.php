<?php

use Illuminate\Support\Facades\Route;
use TeacherAi\Integration\Infrastructure\Http\Controllers\IntegrationController;

Route::prefix('integration')
    ->group(function () {
        Route::post('/image', [IntegrationController::class, 'image'])->name('integration.image');
    });
