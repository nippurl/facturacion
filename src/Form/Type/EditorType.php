<?php
/**
 * Created by PhpStorm.
 * User: jme
 * Date: 15/04/18
 * Time: 09:13
 */

namespace App\Form\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditorType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'attr' =>[
                'class' => 'ckeditor',
           //     'onLoad' => 'CKEDITOR.replace("this");',
            ]
        ]);
    }

    public function getParent()
    {
        return TextareaType::class;
    }


}