<?php

namespace App\Form;

use App\Entity\Item;
use App\Entity\Producto;
use App\Entity\Usuario;
use App\Repository\UsuarioRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codigo', HiddenType::class)
            ->add('descripcion')
            
            ->add('precioU', NumberType::class, [
                'attr'=>[
                    'onChange'=>'calcular();'
                ]
            ])
            ->add('cantidad', NumberType::class, [
                'attr'=>[
                    'onChange'=>'calcular();'
                ]
            ])
            ->add('total')
            ->add('docid', HiddenType::class, [

            ])
            ->add('producto', EntityType::class, [
                'class' => Producto::class,
                'required' => false,
                'attr'=>[
                    'class' => 'select2',
                    'onChange'=>'CambiaProducto();'
                ]
            ])
            ->add('vendedor', EntityType::class,[
                'class' => Usuario::class,
                'query_builder' => function ( $er) {
                /** @var $er UsuarioRepository */
                    return $er->createQueryBuilder('u')
                        ->where('u.visible != 0')
                        ->orderBy('u.nombre', 'ASC');
                }
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Item::class,
        ]);
    }
}
