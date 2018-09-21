<?php

namespace App\PaymentGateway;

use App\Form\Model\Payment;

abstract class AbstractPaymentGateway implements PaymentGatewayInterface
{
    /**
     * {@inheritdoc}
     */
    abstract public function submitPayment(Payment $payment);

    /**
     * {@inheritdoc}
     */
    public function isCompatible(Payment $payment) {
        return true;
    }
}
