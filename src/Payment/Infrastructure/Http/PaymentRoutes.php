<?php

use Illuminate\Support\Facades\Route;
use TeacherAi\Payment\Infrastructure\Http\Controllers\ClientController;
use TeacherAi\Payment\Infrastructure\Http\Controllers\PaymentController;

Route::prefix('client')
    ->middleware('auth:sanctum')
    ->group(function () {
       Route::post('/', [ClientController::class, 'store'])->name('client.store');
       Route::get('/{cpf}', [ClientController::class, 'show'])->name('client.show');
    });

Route::prefix('payment')
    ->middleware('auth:sanctum')
    ->group(function () {
        Route::post('/boleto', [PaymentController::class, 'createOrderBoleto'])->name('payment.boleto');
        Route::post('/pix', [PaymentController::class, 'createOrderPix'])->name('payment.pix');
        Route::post('/credit_card', [PaymentController::class, 'createOrderCreditCard'])->name('payment.credit_card');
        Route::post('/confirm/received/{id}/{plan}', [PaymentController::class, 'confirmReceived'])->name('payment.confirm.received');
    });
