<?php

namespace App\Form;

use App\Entity\Gasto;
use App\Form\Type\FechaType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GastoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fecha', FechaType::class,[
                'constraints' => [

                ]
            ])
            ->add('gastoTipo', null,[
                'label' => 'Tipo de Gasto'
            ])
            ->add('descripcion')
            ->add('importe')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Gasto::class,
        ]);
    }
}
