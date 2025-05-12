<?php

namespace App\Form;

use App\Entity\Visita;
use App\Entity\Coche;
use App\Entity\Plaza;
use App\Entity\Estado;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VisitaTypeForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('coche', EntityType::class, [
                'class' => Coche::class,
                'choice_label' => 'Matricula', // esto es lo que muestra
                'placeholder' => 'Selecciona un coche',
            ])
            ->add('plaza', EntityType::class, [
                'class' => Plaza::class,
                'choice_label' => 'idPlaza',
            ])
            ->add('estado', EntityType::class, [
                'class' => Estado::class,
                'choice_label' => 'nombre', // ajusta según tu propiedad real
            ])
            ->add('entrada', DateTimeType::class, [
                'widget' => 'single_text',
                'required' => false,
            ])
            ->add('salida', DateTimeType::class, [
                'widget' => 'single_text',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Visita::class,
        ]);
    }
}
