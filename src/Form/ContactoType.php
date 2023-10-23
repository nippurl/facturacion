<?php

namespace App\Form;

use App\Entity\Contacto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('razon')
            ->add('cuil',null,[
                'label' => 'CUIL/CUIT'
            ])
            ->add('fechaNac',DateType::class, array_merge(DocCabType::getDate(),[
                'label'=> 'Fecha de Nacimiento',
            ]))
            ->add('direccion')
            ->add('telefono')
            ->add('observaciones')
        /*    ->add('Guardar', SubmitType::class, [
                'attr' => [
                    'onclick'=> "alert('ejecuta');",
                ]

            ])/**/
        ;

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Contacto::class,
        ]);
    }
}
