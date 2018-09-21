<?php

namespace App\PaymentGateway;

use App\Form\Model\Payment;

interface PaymentGatewayInterface
{
    /**
     * Submit payment for settlement.
     *
     * @param Payment $payment
     *
     * @return bool
     */
    public function submitPayment(Payment $payment);

    /**
     * Check if the payment gateway can be used.
     *
     * @param Payment $payment
     *
     * @return bool
     */
    public function isCompatible(Payment $payment);
}
