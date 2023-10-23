<?php

namespace App\Form;

use App\Entity\Pago;
use App\Entity\PagoForma;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PagoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('forma', EntityType::class, [
                'class' => PagoForma::class,
                'attr' => [
                    'onChange' => 'PagoCalcular();'
                ]
            ])
            ->add('monto', NumberType::class,[
                'attr' => [
                    'onChange' => 'PagoCalcular();'
                ]
            ])
            ->add('cuotas',HiddenType::class)


            ->add('interes',HiddenType::class)
            ->add('montoCuota',HiddenType::class)
            ->add('montoTotal',HiddenType::class)
            ->add('documentoId', HiddenType::class )

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Pago::class,
        ]);
    }
}
