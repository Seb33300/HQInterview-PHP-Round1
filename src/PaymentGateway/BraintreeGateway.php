<?php

namespace App\PaymentGateway;

use App\Form\Model\Payment;
use Braintree\Gateway;

class BraintreeGateway extends AbstractPaymentGateway
{
    /** @var Gateway */
    private $gateway;

    /**
     * BraintreeGateway constructor.
     */
    public function __construct()
    {
        // Check if Braintree env vars are defined
        foreach (['BRAINTREE_ENVIRONMENT', 'BRAINTREE_MERCHANT_ID', 'BRAINTREE_PUBLIC_KEY', 'BRAINTREE_PRIVATE_KEY'] as $env) {
            if (!getenv($env)) {
                throw new \Exception(sprintf('The environment var %s must be defined.', $env));
            }
        }

        // New Braintree gateway
        $this->gateway = new Gateway([
            'environment' => getenv('BRAINTREE_ENVIRONMENT'),
            'merchantId' => getenv('BRAINTREE_MERCHANT_ID'),
            'publicKey' => getenv('BRAINTREE_PUBLIC_KEY'),
            'privateKey' => getenv('BRAINTREE_PRIVATE_KEY'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function submitPayment(Payment $payment)
    {
        $result = $this->gateway->transaction()->sale([
            'amount' => $payment->getOrder()->getPrice(),
            'creditCard' => [
                'cardholderName' => $payment->getCreditCard()->getCardholderName(),
                'number' => $payment->getCreditCard()->getNumber(),
                'expirationMonth' => $payment->getCreditCard()->getExpirationMonth(),
                'expirationYear' => $payment->getCreditCard()->getExpirationYear(),
                'cvv' => $payment->getCreditCard()->getCvv(),
            ],
            'options' => [
                'submitForSettlement' => true,
            ],
        ]);

        if ($result->success) {

            $payment->getOrder()
                ->setPaymentSuccess(true)
                ->setPaymentIdentifier($result->transaction->id)
                ->setPaymentResponse(json_encode($result->transaction->jsonSerialize()))
            ;

        } else {

            $payment->getOrder()->setPaymentSuccess(false);

            if ($result->errors->count()) {

                $errors = [];
                foreach ($result->errors->deepAll() as $error) {
                    $errors[] = [
                        'attribute' => $error->attribute,
                        'code' => $error->code,
                        'message' => $error->message,
                    ];
                }

                $payment->getOrder()->setPaymentResponse(json_encode($errors));

            } elseif ($result->transaction->id) {

                $payment->getOrder()
                    ->setPaymentIdentifier($result->transaction->id)
                    ->setPaymentResponse(json_encode($result->transaction->jsonSerialize()))
                ;

            }

        }

        $payment->getOrder()->setPaymentGateway('braintree');

        return $payment->getOrder()->isPaymentSuccess();
    }
}
