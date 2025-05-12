<?php

namespace App\Form;

use App\Entity\Plaza;
use App\Entity\Tipo;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PlazaTypeForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
       $builder
            ->add('idPlaza', EntityType::class, [
                'class' => Plaza::class,
                'choice_label' => function (Plaza $plaza) {
                    return 'Plaza #' . $plaza->getIdPlaza(); // o cualquier formato útil
                },
                'label' => 'Seleccionar Plaza',
                'placeholder' => 'Seleccione una plaza',
                'mapped' => false, // No vinculado directamente a la propiedad de la entidad
                'required' => false,
            ])
            ->add('tipo', EntityType::class, [
                'class' => Tipo::class,
                'choice_label' => 'nombre',
                'label' => 'Tipo de plaza',
                'placeholder' => 'Seleccione un tipo',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Plaza::class,
        ]);
    }
}
