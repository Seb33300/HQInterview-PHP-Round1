<?php

namespace App\Tests\PaymentGateway;

use App\Form\Model\Payment;
use App\PaymentGateway\BraintreeGateway;
use App\PaymentGateway\PaymentGatewayChain;
use App\PaymentGateway\PaypalGateway;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class PaymentGatewayChainTest extends KernelTestCase
{
    use PaymentGatewayTestTrait;

    /**
     * @return array
     */
    public function providePaypalPayments()
    {
        return [
            [$this->createPayment('USD', 'AMEX')],
            [$this->createPayment('USD', 'VISA')],
            [$this->createPayment('EUR', 'MASTERCARD')],
            [$this->createPayment('AUD', 'VISA')],
        ];
    }

    /**
     * @return array
     */
    public function provideBraintreePayments()
    {
        return [
            [$this->createPayment('THB', 'MASTERCARD')],
            [$this->createPayment('HKD', 'VISA')],
            [$this->createPayment('SGD', 'DISCOVER')],
        ];
    }

    /**
     * @param Payment $payment
     *
     * @dataProvider providePaypalPayments
     */
    public function testPaypalGateway(Payment $payment)
    {
        self::bootKernel();

        $paymentGatewayChain = self::$container->get(PaymentGatewayChain::class);

        $this->assertInstanceOf(PaypalGateway::class, $paymentGatewayChain->getPaymentGateway($payment));
    }

    /**
     * @param Payment $payment
     *
     * @dataProvider provideBraintreePayments
     */
    public function testBraintreeGateway(Payment $payment)
    {
        self::bootKernel();

        $paymentGatewayChain = self::$container->get(PaymentGatewayChain::class);

        $this->assertInstanceOf(BraintreeGateway::class, $paymentGatewayChain->getPaymentGateway($payment));
    }

    /**
     * @return array
     */
    protected function getCreditCards()
    {
        return [
            'MASTERCARD' => ['5211040000913871', '555'],
            'VISA' => ['4916237293060995', '555'],
            'AMEX' => ['345850286290186', '5555'],
            'DISCOVER' => ['6011574954720874', '555'],
        ];
    }
}
