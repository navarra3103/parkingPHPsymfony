<?php

namespace App\Form;

use App\Entity\Plaza;
use App\Entity\Tipo;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class AddPlazaTypeForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('idPlaza', TextType::class, [
                'label' => 'ID de la plaza',
            ])
            ->add('tipo', EntityType::class, [
                'class' => Tipo::class,
                'choice_label' => 'nombre',
                'label' => 'Tipo de plaza',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Plaza::class,
        ]);
    }
}
