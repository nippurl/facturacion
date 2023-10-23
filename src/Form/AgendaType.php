<?php

namespace App\Form;

use App\Entity\Agenda;
use App\Entity\Contacto;
use App\Entity\Producto;
use App\Entity\Usuario;
use App\Form\Type\FechaType;
use App\Form\Type\HoraType;
use App\Repository\UsuarioRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AgendaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('fecha', FechaType::class)
            ->add('hora', HoraType::class, [
                'attr' => [
                    'step' => '1800',
                ]

            ])
            ->add('duracion', ChoiceType::class,[
                'choices'=> Agenda::DuracionArray(),
                'expanded' => true,
            ])
            ->add('usuario', EntityType::class,[
                'class' => Usuario::class,
                'query_builder'=>function(UsuarioRepository $UR){
                    return $UR->createQueryBuilder('u')
                        ->innerJoin('u.orden','o')
                        ->orderBy('u.nombre');
                },
            ])

            ->add('contacto', EntityType::class, [
                'class' => Contacto::class,
                'attr' => [
                    'class' => 'select2',
            //       'onChange' => 'cargaContacto();'
                ]
            ])
            ->add('agregar', ButtonType::class, [
                'label' => 'Nuevo Contacto',
                'attr' => [
                    'onClick' => 'nuevoContacto();'
                ]
            ])
            ->add('productos', EntityType::class, [
                'class' => Producto::class,
                'multiple' => true,

                'attr' => [
                    'class' => 'select2',

                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Agenda::class,
        ]);
    }
}
