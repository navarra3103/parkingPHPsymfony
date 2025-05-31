<?php
// src/Form/RegistrationFormType.php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType; // Para el campo de contraseña
use Symfony\Component\Form\Extension\Core\Type\RepeatedType; // Para la confirmación de contraseña
use Symfony\Component\Form\Extension\Core\Type\TextType;     // Para el campo de username
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex; // Para validar la contraseña

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, [
                'label' => 'Nombre de Usuario',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Por favor, introduce un nombre de usuario.',
                    ]),
                    new Length([
                        'min' => 3,
                        'minMessage' => 'Tu nombre de usuario debe tener al menos {{ limit }} caracteres.',
                        'max' => 180,
                        'maxMessage' => 'Tu nombre de usuario no puede tener más de {{ limit }} caracteres.',
                    ]),
                ],
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => [
                    'attr' => ['autocomplete' => 'new-password'],
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Por favor, introduce una contraseña.',
                        ]),
                        new Length([
                            'min' => 6,
                            'minMessage' => 'Tu contraseña debe tener al menos {{ limit }} caracteres.',
                            'max' => 4096, // Longitud máxima que acepta Symfony para contraseñas hasheadas
                        ]),
                        // Opcional: Añadir requisitos de complejidad a la contraseña
                        new Regex([
                            'pattern' => '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*()_\-+=])[A-Za-z\d!@#$%^&*()_\-+=]{8,}$/',
                            'message' => 'La contraseña debe tener al menos 8 caracteres, incluyendo una letra mayúscula, una minúscula, un número y un símbolo especial (como @, #, $, %, etc.).',
                        ]),
                    ],
                    'label' => 'Contraseña',
                ],
                'second_options' => [
                    'attr' => ['autocomplete' => 'new-password'],
                    'label' => 'Repetir Contraseña',
                ],
                'invalid_message' => 'Las contraseñas no coinciden.',
                // Instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false, // Importante: Este campo no se mapea directamente a la entidad User
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}