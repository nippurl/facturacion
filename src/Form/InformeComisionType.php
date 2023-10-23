<?php

namespace App\Form;

use App\Entity\DocumentoTipo;
use App\Entity\ProductoTipo;
use App\Entity\Usuario;
use App\Entity\PagoForma;
use App\Form\Type\FechaType;
use App\Repository\UsuarioRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InformeComisionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('desde', FechaType::class, [
                'data' => new \DateTime(),
            ])
            ->add('hasta', FechaType::class, [
                'data' => new \DateTime()
            ])
            ->add('tipo', EntityType::class, [
                'label' => 'Tipo de Documento',
                'multiple' => true,
                'expanded' => true,
                'class' => DocumentoTipo::class,
                'required' => false,
            ])
            ->add('tipoProducto', EntityType::class, [
                'label' => 'Tipo de Producto',
                'class' => ProductoTipo::class,
                'required' => false,
            ])
            ->add('vendedor', EntityType::class, [
                'class' => Usuario::class,
                'query_builder' => function (UsuarioRepository $er) {
                    return $er->qryVisibles();
                },
                'required' => false,
            ])
            ->add('pagoForma', EntityType::class, [
                'label' => 'Forma de Pago',
                'class' => PagoForma::class,
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('Filtrar', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
