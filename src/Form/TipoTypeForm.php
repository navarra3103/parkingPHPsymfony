<?php

namespace App\Form;

use App\Entity\Tipo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TipoTypeForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('idTipo', EntityType::class, [
                'class' => Tipo::class,
                'choice_label' => function (Tipo $tipo) {
                    return 'Tipo #' . $tipo->getIdTipo() . ' - ' . $tipo->getNombre();
                },
                'label' => 'Seleccionar Tipo',
                'placeholder' => 'Seleccione un tipo',
                'mapped' => false,
                'required' => false,
            ])
            ->add('nombre', TextType::class, [
                'label' => 'Nuevo Nombre',
            ])
            ->add('color', TextType::class, [
                'label' => 'Nuevo Color',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Tipo::class,
        ]);
    }
}
