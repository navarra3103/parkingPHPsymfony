<?php

namespace App\Form;

use App\Entity\Coche;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType; // Ejemplo de un tipo de campo

class CocheTypeForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Matricula', TextType::class) // Define el campo 'Matricula'
            // Puedes añadir más campos aquí, por ejemplo:
            // ->add('modelo', TextType::class)
            // ->add('marca', TextType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Coche::class,
        ]);
    }
}