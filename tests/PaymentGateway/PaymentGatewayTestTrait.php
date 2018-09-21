<?php

namespace App\Tests\PaymentGateway;

use App\Entity\Order;
use App\Form\Model\CreditCard;
use App\Form\Model\Payment;

trait PaymentGatewayTestTrait
{
    /**
     * @param float  $price
     * @param string $currency
     *
     * @return Order
     */
    protected function createOrder($price, $currency)
    {
        $order = new Order();

        $order
            ->setPrice($price)
            ->setCurrency($currency)
            ->setFullName('Payment Test')
        ;

        return $order;
    }

    /**
     * @param string $type
     *
     * @return CreditCard
     */
    protected function createCreditCard($type)
    {
        $creditCard = new CreditCard();

        $creditCard
            ->setCardholderName('Holder Name')
            ->setNumber($this->getCreditCards()[$type][0])
            ->setExpirationMonth(12)
            ->setExpirationYear(2025)
            ->setCvv($this->getCreditCards()[$type][1])
        ;

        return $creditCard;
    }

    /**
     * @param string $currency
     * @param string $cardType
     * @param float  $price
     *
     * @return Payment
     */
    protected function createPayment($currency, $cardType, $price = 5.00)
    {
        $payment = new Payment();

        $payment
            ->setOrder($this->createOrder($price, $currency))
            ->setCreditCard($this->createCreditCard($cardType))
        ;

        return $payment;
    }
}
