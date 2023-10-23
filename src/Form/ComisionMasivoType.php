<?php

namespace App\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PercentType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ComisionMasivoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('comision', PercentType::class, [
                'required' =>false
            ])
            ->add('producto', TextType::class, [
                'required' =>false
            ])
            ->add('vendedor', EntityType::class, [
                'class' => Usuario::class,

                'required' =>false
            ])
            ->add('Guardar',SubmitType::class)
            ->add('comision', PercentType::class,[

            ])
            ->add('incremento',PercentType::class,[])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'filtro' => [],
            'selected' => [],
            // Configure your form options here
        ]);
    }
}
