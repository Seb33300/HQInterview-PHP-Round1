<?php

namespace App\Form\Model;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Validation;

class CreditCard
{
    /**
     * @var string
     *
     * @Assert\NotBlank()
     */
    private $cardholderName;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @Assert\Luhn()
     */
    private $number;

    /**
     * @var int
     *
     * @Assert\NotBlank()
     * @Assert\Range(min = 1, max = 12)
     */
    private $expirationMonth;

    /**
     * @var int
     *
     * @Assert\NotBlank()
     * @Assert\Range(min = 2018, max = 2050)
     */
    private $expirationYear;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @Assert\Length(min = 3, max = 4)
     * @Assert\Regex("/^\d+$/")
     */
    private $cvv;

    /**
     * @return string
     */
    public function getCardholderName()
    {
        return $this->cardholderName;
    }

    /**
     * @param string $cardholderName
     *
     * @return $this
     */
    public function setCardholderName(string $cardholderName)
    {
        $this->cardholderName = $cardholderName;

        return $this;
    }

    /**
     * @return string
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @param string $number
     *
     * @return $this
     */
    public function setNumber(string $number)
    {
        $this->number = $number;
        return $this;
    }

    /**
     * @return int
     */
    public function getExpirationMonth()
    {
        return $this->expirationMonth;
    }

    /**
     * @param int $expirationMonth
     *
     * @return $this
     */
    public function setExpirationMonth(int $expirationMonth)
    {
        $this->expirationMonth = $expirationMonth;

        return $this;
    }

    /**
     * @return int
     */
    public function getExpirationYear()
    {
        return $this->expirationYear;
    }

    /**
     * @param int $expirationYear
     *
     * @return $this
     */
    public function setExpirationYear(int $expirationYear)
    {
        $this->expirationYear = $expirationYear;

        return $this;
    }

    /**
     * @return string
     */
    public function getCvv()
    {
        return $this->cvv;
    }

    /**
     * @param string $cvv
     *
     * @return $this
     */
    public function setCvv(string $cvv)
    {
        $this->cvv = $cvv;

        return $this;
    }

    /**
     * @return string
     *
     * @throws \Exception
     */
    public function getType()
    {
        if (!$this->getNumber()) {
            return null;
        }

        $validator = Validation::createValidator();

        foreach (['AMEX', 'CHINA_UNIONPAY', 'DINERS', 'DISCOVER', 'INSTAPAYMENT', 'JCB', 'LASER', 'MAESTRO', 'MASTERCARD', 'VISA'] as $type) {
            if ($validator->validate($this->getNumber(), [new Assert\CardScheme($type)])->count() == 0) {
                return $type;
            }
        }

        throw new \Exception('Unable to determine credit card type.');
    }

    /**
     * @param ExecutionContextInterface $context
     * @param mixed                     $payload
     *
     * @Assert\Callback()
     */
    public function validate(ExecutionContextInterface $context, $payload)
    {
        if ($this->getType() === 'AMEX' && mb_strlen($this->getCvv()) === 3) {
            $context
                ->buildViolation('CVV must be 4 digits.')
                ->atPath('cvv')
                ->addViolation()
            ;
        } elseif ($this->getType() !== 'AMEX' && mb_strlen($this->getCvv()) === 4) {
            $context
                ->buildViolation('CVV must be 3 digits.')
                ->atPath('cvv')
                ->addViolation()
            ;
        }
    }
}
