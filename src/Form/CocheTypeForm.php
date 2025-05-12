<?php

namespace App\Form;

use App\Entity\Coche;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CocheTypeForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('matricula', EntityType::class, [
                'class' => Coche::class,
                'choice_label' => 'matricula',
                'placeholder' => 'Selecciona una matrícula',
                'mapped' => false, // No sobrescribe el campo de entidad directamente
            ])
            ->add('marca', TextType::class, [
                'required' => false,
            ])
            ->add('modelo', TextType::class, [
                'required' => false,
            ])
            ->add('color', TextType::class, [
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Coche::class,
        ]);
    }
}
