<?php

namespace App\Form\Model;

use App\Entity\Order;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class Payment
{
    /**
     * @var Order
     *
     * @Assert\NotBlank()
     * @Assert\Valid()
     */
    private $order;

    /**
     * @var CreditCard
     *
     * @Assert\NotBlank()
     * @Assert\Valid()
     */
    private $creditCard;

    /**
     * @return Order
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @param Order $order
     *
     * @return $this
     */
    public function setOrder(Order $order)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * @return CreditCard
     */
    public function getCreditCard()
    {
        return $this->creditCard;
    }

    /**
     * @param CreditCard $creditCard
     *
     * @return $this
     */
    public function setCreditCard(CreditCard $creditCard)
    {
        $this->creditCard = $creditCard;

        return $this;
    }

    /**
     * @param ExecutionContextInterface $context
     * @param mixed                     $payload
     *
     * @Assert\Callback()
     */
    public function validate(ExecutionContextInterface $context, $payload)
    {
        if ($this->getCreditCard()->getType() === 'AMEX' && $this->getOrder()->getCurrency() !== 'USD') {
            $context
                ->buildViolation('AMEX is available only for US Dollar.')
                ->atPath('creditCard.number')
                ->addViolation()
            ;
        }
    }
}
