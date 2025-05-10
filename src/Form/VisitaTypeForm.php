<?php

namespace App\Form;

use App\Entity\Visita;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class VisitaTypeForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('entrada', DateTimeType::class, [
                'widget' => 'single_text',
                'html5' => false, // Desactiva el control HTML5
                'format' => 'yyyy-MM-dd HH:mm:ss', // Especifica tu formato
            ])
            ->add('salida', DateTimeType::class, [
                'widget' => 'single_text',
                'html5' => false, // Desactiva el control HTML5
                'format' => 'yyyy-MM-dd HH:mm:ss',
                'required' => false,
            ])
            ->add('estado', EntityType::class, [
                'class' => 'App\Entity\Estado',
                'choice_label' => 'Nombre', // Asegúrate de que el campo visible es "Nombre" en Estado
            ])
            ->add('coche', EntityType::class, [
                'class' => 'App\Entity\Coche',
                'choice_label' => 'Matricula', // Usando "Matricula" como el campo visible en Coche
            ])
            ->add('plaza', EntityType::class, [
                'class' => 'App\Entity\Plaza',
                'choice_label' => 'Nombre', // Asegúrate de que el campo visible es "Nombre" en Plaza
                'required' => false, // Si no es obligatorio seleccionar una plaza, añade esto
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Visita::class,
        ]);
    }
}
