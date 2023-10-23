<?php
/**
 * Created by PhpStorm.
 * User: jme
 * Date: 24/03/18
 * Time: 09:15
 */

namespace App\Form\Type;


use App\Entity\DocumentoTipo;
use App\Entity\Usuario;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;


class TipoDocType extends AbstractType

{
    public function configureOptions(OptionsResolver $resolver)
    {
        /** @var Usuario $user */
        $user = Usuario::class;
        $resolver->setDefaults([
           'class' => DocumentoTipo::class,
            'queryBuilder' => function (EntityRepository $er) use ($user) {
                    $qry =  $er->createQueryBuilder('u');
                if ($user->getBlanco()) {
                    $qry->andWhere('u.blanco=1');
                }

                return $qry;
            },

        ]);
    }

    public function getParent()
    {
        return EntityType::class;
    }
}