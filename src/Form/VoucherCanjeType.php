<?php

namespace App\Form;

use App\Entity\VoucherCanje;
use App\Form\Type\FechaType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VoucherCanjeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('voucher', null, [
                'disabled' => true,

            ])
            ->add('fecha', FechaType::class)
            ->add('comanda', NumberType::class)

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data' => VoucherCanje::class
            // Configure your form options here
        ]);
    }
}
