<?php

namespace App\PaymentGateway;

use App\Form\Model\Payment;
use Doctrine\ORM\EntityManagerInterface;

class PaymentGatewayChain
{
    /** @var PaymentGatewayInterface[] */
    private $paymentGateways;

    /** @var EntityManagerInterface */
    private $entityManager;

    /**
     * @param PaymentGatewayInterface[] $paymentGateways
     * @param EntityManagerInterface    $entityManager
     */
    public function __construct($paymentGateways, EntityManagerInterface $entityManager)
    {
        $this->paymentGateways = $paymentGateways;
        $this->entityManager = $entityManager;
    }

    /**
     * Submit payment for settlement.
     *
     * @param Payment $payment
     *
     * @return bool
     */
    public function submitPayment(Payment $payment)
    {
        try {

            // Submit payment
            $this->getPaymentGateway($payment)->submitPayment($payment);

            // Save order
            $this->entityManager->persist($payment->getOrder());
            $this->entityManager->flush();

            return $payment->getOrder()->isPaymentSuccess();

        } catch (\Exception $exception) {
            return false;
        }
    }

    /**
     * @param Payment $payment
     *
     * @return PaymentGatewayInterface
     */
    public function getPaymentGateway(Payment $payment)
    {
        foreach ($this->paymentGateways as $paymentGateway) {
            if ($paymentGateway->isCompatible($payment)) {
                return $paymentGateway;
            }
        }

        throw new \Exception('No gateway available for this payment.');
    }
}
