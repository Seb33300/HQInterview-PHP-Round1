<?php

namespace App\Form\Type;

use App\Entity\Order;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\Loader\CallbackChoiceLoader;
use Symfony\Component\Form\Extension\Core\Type\CurrencyType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Intl\Intl;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderType extends AbstractType
{
    /** @var array */
    private $currencies;

    /**
     * @param array $currencies
     */
    public function __construct($currencies)
    {
        $this->currencies = $currencies;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('price', MoneyType::class, [
                'currency' => false,
            ])
            ->add('currency', CurrencyType::class, [
                'choice_loader' => new CallbackChoiceLoader(function () {
                    $currencyNames = [];

                    foreach ($this->currencies as $currency) {
                        $currencyNames[] = Intl::getCurrencyBundle()->getCurrencyName($currency);
                    }

                    return array_combine($currencyNames, $this->currencies);
                }),
            ])
            ->add('fullName')
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Order::class,
        ]);
    }
}
