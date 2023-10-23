<?php

namespace App\Form;

use App\Entity\Usuario;
use App\Entity\Producto;
use App\Repository\UsuarioRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PercentType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ComisionFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
         /*   ->add('comision', PercentType::class, [
                'required' =>false
            ])*/
            ->add('producto', EntityType::class, [
                'class' => Producto::class,
                'required' =>false,
            ])
            ->add('vendedor', EntityType::class, [
                'class' => Usuario::class,
                'query_builder'=> function (UsuarioRepository $er) {
                    return $er->qryVisibles();
                },

                'required' =>false
            ])
            ->add('filtrar', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'method' => 'POST',
          //  'data_class' => Comision::class,
        ]);
    }
}
