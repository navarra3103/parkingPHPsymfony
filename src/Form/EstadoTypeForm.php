<?php

namespace App\Form;

use App\Entity\Estado;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EstadoTypeForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('idEstado', EntityType::class, [
                'class' => Estado::class,
                'choice_label' => function (Estado $estado) {
                    return 'Estado #' . $estado->getIdEstado() . ' - ' . $estado->getNombre();
                },
                'label' => 'Seleccionar Estado',
                'placeholder' => 'Seleccione un estado',
                'mapped' => false,
                'required' => false,
            ])
            ->add('nombre', TextType::class, [
                'label' => 'Nuevo Nombre',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Estado::class,
        ]);
    }
}
