<?php

namespace App\Tests\PaymentGateway;

use App\PaymentGateway\PaypalGateway;

class PaypalGatewayTest extends AbstractPaymentGatewayTest
{
    /**
     * {@inheritdoc}
     */
    public function provideCompatiblePayments()
    {
        return [
            [$this->createPayment('EUR', 'MASTERCARD')],
            [$this->createPayment('USD', 'AMEX')],
            [$this->createPayment('AUD', 'VISA')],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function provideIncompatiblePayments()
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
    public function provideFailureCreditCards()
    {
        return [
            [$this->createPayment('EUR', 'ERROR')],
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function getPaymentGateway()
    {
        return new PaypalGateway();
    }

    /**
     * {@inheritdoc}
     */
    protected function getCreditCards()
    {
        return [
            'MASTERCARD' => ['5211040000913871', '555'],
            'VISA' => ['4916237293060995', '555'],
            'AMEX' => ['345850286290186', '5555'],
            'DISCOVER' => ['6011574954720874', '555'],
            'ERROR' => ['345850286290186', '555'], // AMEX with 3-digits CVV
        ];
    }
}
