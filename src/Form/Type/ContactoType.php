<?php
/**
 * Created by PhpStorm.
 * User: jme
 * Date: 24/03/18
 * Time: 10:02
 */

namespace App\Form\Type;

use App\Entity\Contacto;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;


class ContactoType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {

        $resolver->setDefaults([
            //  'type'=> 'Entity',
            'em' => 'default',
            'class' => Contacto::class,
            'multiple' => false,
            'query_builder' => function ($er) {
                /** @var ContactoRepository $er */
                return $er->createQueryBuilder('x')
                    ->setMaxResults(500);
                // ->orderBy('x.username', 'ASC');
            },
            'choice_value' => null,
            // 'choices' => array(),
            //  'route' => 'contacto_BuscarAjax',
            'attr' => ['data-widget' => 'select2',
                'class' => 'select2Ajax'
            ]
        ]);
    }

    public function getParent()
    {
        return EntityType::class;
    }


}