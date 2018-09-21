<?php

namespace App\Form\Type;

use App\Form\Model\CreditCard;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreditCardType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('cardholderName')
            ->add('number')
            ->add('expirationMonth')
            ->add('expirationYear')
            ->add('cvv')
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CreditCard::class,
        ]);
    }
}
