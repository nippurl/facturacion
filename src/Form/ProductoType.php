<?php

namespace App\Form;

use App\Entity\Producto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('tipo')
            ->add('nombre')
            ->add('descripcion')
            ->add('codBarra', null, [
                'required' => false,
            ])
            ->add('precio')
            ->add('costo',null,[
                'label'=> 'Precio de Costo',
                'required' => false,
            ])
            ->add('fraccion')

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Producto::class,
        ]);
    }
}
