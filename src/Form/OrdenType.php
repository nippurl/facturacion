<?php

namespace App\Form;

use App\Entity\Orden;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrdenType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('orden')
         //   ->add('createAt')
          //  ->add('updateAt')
            ->add('responsable')
        //    ->add('agendaArea')
        //    ->add('createBy')
        //    ->add('updateBy')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Orden::class,
        ]);
    }
}
