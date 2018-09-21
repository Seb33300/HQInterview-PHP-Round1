<?php

namespace App\Tests\PaymentGateway;

use App\PaymentGateway\BraintreeGateway;

class BraintreeGatewayTest extends AbstractPaymentGatewayTest
{
    /**
     * {@inheritdoc}
     */
    public function provideCompatiblePayments()
    {
        return [
            [$this->createPayment('THB', 'MASTERCARD')],
            [$this->createPayment('HKD', 'VISA')],
            [$this->createPayment('SGD', 'DISCOVER')],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function provideIncompatiblePayments()
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function provideFailureCreditCards()
    {
        return [
            // Braintree require payment amount between 2000.00 - 3000.99 for declined payments
            [$this->createPayment('EUR', 'ERROR', 2555)],
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function getPaymentGateway()
    {
        return new BraintreeGateway();
    }

    /**
     * {@inheritdoc}
     */
    protected function getCreditCards()
    {
        return [
            'MASTERCARD' => ['5555555555554444', '555'],
            'VISA' => ['4111111111111111', '555'],
            'AMEX' => ['378282246310005', '5555'],
            'DISCOVER' => ['6011000990139424', '555'],
            'ERROR' => ['4000111111111115', '555'], // VISA processor declined
        ];
    }
}
