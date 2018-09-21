<?php

namespace App\Tests\PaymentGateway;

use App\Form\Model\Payment;
use App\PaymentGateway\PaymentGatewayInterface;
use PHPUnit\Framework\TestCase;

abstract class AbstractPaymentGatewayTest extends TestCase
{
    use PaymentGatewayTestTrait;

    /**
     * @return array
     */
    abstract public function provideCompatiblePayments();

    /**
     * @return array
     */
    abstract public function provideIncompatiblePayments();

    /**
     * @return array
     */
    abstract public function provideFailureCreditCards();

    /**
     * @param Payment $payment
     *
     * @dataProvider provideCompatiblePayments
     */
    public function testCompatiblePayment(Payment $payment)
    {
        $paymentGateway = $this->getPaymentGateway();

        $this->assertTrue($paymentGateway->isCompatible($payment));
    }

    /**
     * @param Payment $payment
     *
     * @dataProvider provideIncompatiblePayments
     */
    public function testIncompatiblePayment(Payment $payment)
    {
        $paymentGateway = $this->getPaymentGateway();

        $this->assertFalse($paymentGateway->isCompatible($payment));
    }

    /**
     * @param Payment $payment
     *
     * @dataProvider provideCompatiblePayments
     */
    public function testPaymentSuccess(Payment $payment)
    {
        $paymentGateway = $this->getPaymentGateway();

        $this->assertTrue($paymentGateway->submitPayment($payment));
    }

    /**
     * @param Payment $payment
     *
     * @dataProvider provideFailureCreditCards
     */
    public function testPaymentFailure(Payment $payment)
    {
        $paymentGateway = $this->getPaymentGateway();

        $this->assertFalse($paymentGateway->submitPayment($payment));
    }

    /**
     * @return PaymentGatewayInterface
     */
    abstract protected function getPaymentGateway();
}
