<?php
/**
 * Created by PhpStorm.
 * User: jme
 * Date: 24/03/18
 * Time: 09:15
 */

namespace App\Form\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\OptionsResolver\OptionsResolver;


class FechaType extends AbstractType

{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'widget' => 'single_text',
            //  'label'=> 'Nueva Fecha:',
            //'type'=> 'date',

             // 'format' => 'yyyy-MM-dd',
            'format' => 'dd-MM-yyyy',
            'html5'=> true,
          //  'html5' => 'true',
          //  'mapped' => false,
         // 'model_timezone' => 'America/Argentina/Tucuman',
         //   'pattern' => 'dd-MM-yyyy',
            'attr' => [
                'type' => 'date',
                'class' => 'date'
            ]
        ]);
    }

    public function getParent()
    {
        return DateType::class;
    }
}