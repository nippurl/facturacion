<?php

namespace App\Form;

use App\Entity\Contacto;
use App\Entity\Documento;
use App\Repository\ContactoRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DocCabType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id', HiddenType::class)
            ->add('fecha', DateType::class, self::getDate())
            ->add('numero', NumberType::class)
            ->add('total', HiddenType::class, [

            ])
            ->add('contacto', EntityType::class, [
                'class'   => Contacto::class,
                'choices' => [],
                'attr'    => [
                    'class'    => 'select2 contacto',
                    'onChange' => 'cargaContacto();'
                ]
            ])
            ->add('agregar', ButtonType::class, [
                'label' => 'Nuevo Contacto',
                'attr'  => [
                    'onClick' => 'nuevoContacto();'
                ]
            ])/*       ->add('Guardar', SubmitType::class, [
            'label'=>'guardar',
            'attr'=>[
                'class'=> 'new',
            ]
        ])*/
        ;
        $builder->addEventListener(
            FormEvents::PRE_SUBMIT,
            function (FormEvent $event) {
                $data = $event->getData();
                $form = $event->getForm();
                if (isset($data['contacto']) and $data['contacto'] != null) {
                    $selected = $data['contacto'];
                    $form->add('contacto', EntityType::class, array(
                        'class'         => Contacto::class,
                        'attr'          => [
                            'class'    => 'select2 contacto',
                            'onChange' => 'cargaContacto();'
                        ],
                        'query_builder' => function (ContactoRepository $er) use ($selected) {
                            return $er->createQueryBuilder('a')
                                ->where('a.id = :id')
                                ->setParameter('id', $selected)
                            ;
                        },
                    ));
                }
            }
        );


    }

    static public function getDate(array $arr = array()): array
    {
        $xx = [
            'required' => false,
            'widget'   => 'single_text',
            //  'label'=> 'Nueva Fecha:',
            //'type'=> 'date',
            'format'   => 'dd-MM-yyyy',
            'html5'    => true,
            //  'mapped' => false,
            //  'by_reference' => true,
            'attr'     => [
                'class' => 'date'
            ]
        ];

        return array_merge($xx, $arr);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Documento::class,

        ]);
    }
}
