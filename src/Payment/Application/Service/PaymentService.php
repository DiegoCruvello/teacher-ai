<?php

namespace TeacherAi\Payment\Application\Service;

use TeacherAi\Payment\Application\DTO\InputConfirmReceived;
use TeacherAi\Payment\Application\DTO\InputCreateOrder;
use TeacherAi\Payment\Domain\Entity\Boleto;
use TeacherAi\Payment\Domain\Entity\CreditCard;
use TeacherAi\Payment\Domain\Entity\Pix;
use TeacherAi\Payment\Domain\Exception\PaymentDomainException;
use TeacherAi\Payment\Domain\Repository\PaymentRepositoryInterface;

class PaymentService
{
    public function __construct(
        public PaymentRepositoryInterface $repository,
    ) {
    }

    public function createOrderCreditCard(InputCreateOrder $dto): CreditCard
    {
        return $this->repository->createOrderCreditCard($dto);
    }

    public function createOrderBoleto(InputCreateOrder $dto): Boleto
    {
        return $this->repository->createOrderBoleto($dto);
    }

    public function createOrderPix(InputCreateOrder $dto): Pix
    {
        return $this->repository->createOrderPix($dto);
    }

    public function confirmReceived(InputConfirmReceived $dto, string $id): Pix|Boleto
    {
        return $this->repository->confirmReceivedBoleto($dto, $id);
    }
}
