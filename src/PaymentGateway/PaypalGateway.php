<?php

namespace App\PaymentGateway;

use App\Form\Model\Payment;
use PayPal\Api\Amount;
use PayPal\Api\CreditCard;
use PayPal\Api\FundingInstrument;
use PayPal\Api\Payer;
use PayPal\Api\Transaction;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Exception\PayPalConnectionException;
use PayPal\Rest\ApiContext;

class PaypalGateway extends AbstractPaymentGateway
{
    /** @var ApiContext */
    private $apiContext;

    /**
     * PaypalGateway constructor.
     */
    public function __construct()
    {
        // Check if Paypal env vars are defined
        foreach (['PAYPAL_CLIENT_ID', 'PAYPAL_CLIENT_SECRET'] as $env) {
            if (!getenv($env)) {
                throw new \Exception(sprintf('The environment var %s must be defined.', $env));
            }
        }

        // New Paypal API context
        $this->apiContext = new ApiContext(
            new OAuthTokenCredential(
                getenv('PAYPAL_CLIENT_ID'),
                getenv('PAYPAL_CLIENT_SECRET')
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    public function submitPayment(Payment $payment)
    {
        $creditCard = new CreditCard();
        $creditCard
            ->setType(mb_strtolower($payment->getCreditCard()->getType()))
            ->setNumber($payment->getCreditCard()->getNumber())
            ->setExpireMonth($payment->getCreditCard()->getExpirationMonth())
            ->setExpireYear($payment->getCreditCard()->getExpirationYear())
            ->setCvv2($payment->getCreditCard()->getCvv())
        ;

        $fundingInstrument = new FundingInstrument();
        $fundingInstrument->setCreditCard($creditCard);

        $payer = new Payer();
        $payer
            ->setPaymentMethod('credit_card')
            ->setFundingInstruments([$fundingInstrument])
        ;

        $amount = new Amount();
        $amount
            ->setCurrency($payment->getOrder()->getCurrency())
            ->setTotal($payment->getOrder()->getPrice())
        ;

        $transaction = new Transaction();
        $transaction->setAmount($amount);

        $paypalPayment = new \PayPal\Api\Payment();
        $paypalPayment
            ->setIntent('sale')
            ->setPayer($payer)
            ->setTransactions([$transaction])
        ;

        try {

            $paypalPayment->create($this->apiContext);

            $payment->getOrder()
                ->setPaymentSuccess(true)
                ->setPaymentIdentifier($paypalPayment->getId())
                ->setPaymentResponse((string) $paypalPayment)
            ;

        } catch (PayPalConnectionException $exception) {

            $payment->getOrder()
                ->setPaymentSuccess(false)
                ->setPaymentResponse($exception->getData())
            ;

        }

        $payment->getOrder()->setPaymentGateway('paypal');

        return $payment->getOrder()->isPaymentSuccess();
    }

    /**
     * {@inheritdoc}
     */
    public function isCompatible(Payment $payment) {
        return $payment->getCreditCard()->getType() === 'AMEX' || in_array($payment->getOrder()->getCurrency(), ['USD', 'EUR', 'AUD']);
    }
}
