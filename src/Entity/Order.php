<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OrderRepository")
 * @ORM\Table(name="`order`")
 */
class Order
{
    /**
     * @var int
     *
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var float
     *
     * @ORM\Column(type="decimal", precision=10, scale=2)
     *
     * @Assert\NotBlank()
     * @Assert\GreaterThan(value = 0)
     */
    private $price;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=3, options={"fixed" = true})
     *
     * @Assert\NotBlank()
     */
    private $currency;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\NotBlank()
     */
    private $fullName;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    private $paymentSuccess;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=50)
     */
    private $paymentGateway;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $paymentIdentifier;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     */
    private $paymentResponse;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     *
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param float $price
     *
     * @return $this
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @param string $currency
     *
     * @return $this
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * @return string
     */
    public function getFullName()
    {
        return $this->fullName;
    }

    /**
     * @param string $fullName
     *
     * @return $this
     */
    public function setFullName($fullName)
    {
        $this->fullName = $fullName;

        return $this;
    }

    /**
     * @return bool
     */
    public function isPaymentSuccess()
    {
        return $this->paymentSuccess;
    }

    /**
     * @param bool $paymentSuccess
     *
     * @return $this
     */
    public function setPaymentSuccess(bool $paymentSuccess)
    {
        $this->paymentSuccess = $paymentSuccess;

        return $this;
    }

    /**
     * @return string
     */
    public function getPaymentGateway()
    {
        return $this->paymentGateway;
    }

    /**
     * @param string $paymentGateway
     *
     * @return $this
     */
    public function setPaymentGateway(string $paymentGateway)
    {
        $this->paymentGateway = $paymentGateway;

        return $this;
    }

    /**
     * @return string
     */
    public function getPaymentIdentifier()
    {
        return $this->paymentIdentifier;
    }

    /**
     * @param string $paymentIdentifier
     *
     * @return $this
     */
    public function setPaymentIdentifier(string $paymentIdentifier)
    {
        $this->paymentIdentifier = $paymentIdentifier;

        return $this;
    }

    /**
     * @return string
     */
    public function getPaymentResponse()
    {
        return $this->paymentResponse;
    }

    /**
     * @param string $paymentResponse
     *
     * @return $this
     */
    public function setPaymentResponse(string $paymentResponse)
    {
        $this->paymentResponse = $paymentResponse;

        return $this;
    }
}
