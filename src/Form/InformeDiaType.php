<?php

namespace App\Form;

use App\Entity\Usuario;
use App\Form\Type\FechaType;
use App\Form\Type\TipoDocType;
use App\Repository\UsuarioRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InformeDiaType extends AbstractType

{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('desde', FechaType::class)
            ->add('hasta', FechaType::class)
            ->add('tipo', TipoDocType::class, [
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('cajero', EntityType::class, [
                'class' => Usuario::class,

                'required'      => false,
                //'empty_value'   => '--TODOS--',
                //'empty_data'    => null,
                'placeholder' => '--TODOS--',
                //    'choice_label' => 'TODOS',
                'query_builder' => function (UsuarioRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.nick', 'ASC');
                },
            ])
            ->add('filtar', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
