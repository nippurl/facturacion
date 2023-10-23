<?php
/**
 * Created by PhpStorm.
 * User: jme
 * Date: 24/03/18
 * Time: 09:17
 */

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\OptionsResolver\OptionsResolver;


class HoraType extends AbstractType

{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'widget' => 'single_text',
            'required' => false,
            // 'format' => 'HH:ii',
            'attr' => [
                'class' => 'time'
            ]
        ]);
    }

    public function getParent()
    {
        return TimeType::class;
    }
}