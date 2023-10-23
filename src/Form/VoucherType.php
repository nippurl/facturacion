<?php

namespace App\Form;

use App\Entity\Voucher;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VoucherType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('voucherTipo', null, [
                'label' => 'Voucher o Chequera',
            ])
            ->add('numero')
            ->add('compra_fecha', null, DocCabType::getDate())
            ->add('compra_numero')
            ->add('descripcion')

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Voucher::class,
        ]);
    }
}
