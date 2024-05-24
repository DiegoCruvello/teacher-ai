<?php

namespace TeacherAi\Payment\Domain\Repository;

use TeacherAi\Payment\Application\DTO\InputConfirmReceived;
use TeacherAi\Payment\Application\DTO\InputCreateOrder;
use TeacherAi\Payment\Domain\Entity\Boleto;
use TeacherAi\Payment\Domain\Entity\CreditCard;
use TeacherAi\Payment\Domain\Entity\Pix;

interface PaymentRepositoryInterface
{
    public function createOrderCreditCard(InputCreateOrder $dto): CreditCard;
    public function createOrderBoleto(InputCreateOrder $dto): Boleto;
    public function createOrderPix(InputCreateOrder $dto): Pix;
    public function confirmReceivedBoleto(InputConfirmReceived $dto, string $id): Boleto|Pix;

    public function getPaymentStatus(string $paymentId): string;
}

