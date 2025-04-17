<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('email', null, [
            'attr' => [
                'class' => 'home-input' // Clase CSS para el input
            ],
            'label_attr' => [
                'class' => 'home-label' // Clase CSS para el label
            ]
        ])
       // ->add('roles')
       // ->add('password')
        ->add('nombre', null, [
            'attr' => [
                'class' => 'home-input' // Clase CSS para el input
            ],
            'label_attr' => [
                'class' => 'home-label' // Clase CSS para el label
            ]
        ])
        ->add('apellido', null, [
            'attr' => [
                'class' => 'home-input' // Clase CSS para el input
            ],
            'label_attr' => [
                'class' => 'home-label' // Clase CSS para el label
            ]
        ])

         //rempllazo roles y pasword por lo siguiente:

         ->add('roles', ChoiceType::class, [
             'choices' => [
                 'Usuario'=> 'ROLE_USER',
                 'Administrador'=> 'ROLE_SUPER_ADMIN',  #administrador con acceso total a todas las funciones
                 'Directivo'=> 'ROLE_ADMIN', #usuarios como preceptores q no tendran acceso a todas las opciones
                 'Docente' => 'ROLE_DOCENTE',  #acceso a las funciones de DOCENTES
                 'Estudiante' => 'ROLE_ESTUDIANTE',  #acceo a los ESTUDIANTES
             ],
             'expanded' => false,  // Usa select en lugar de radio buttons
             'multiple' => true,   // Permite seleccionar múltiples roles
             'label' => '* Roles',
             'attr' => [
                 'class' => 'home-input'  // Clase CSS para el select o los checkboxes/radio buttons
             ],
             'label_attr' => [
                 'class' => 'home-label'  // Clase CSS para la etiqueta
             ]
         ])
         ->add('password', RepeatedType::class, [
            'type' => PasswordType::class,
            'first_options' => [
                'label' => '* Password',
                'attr' => [
                    'class' => 'home-input'  // Clase CSS para el primer input
                ],
                'label_attr' => [
                    'class' => 'home-label'  // Clase CSS para el primer label
                ]
            ],
            'second_options' => [
                'label' => '* Repetir Password',
                'attr' => [
                    'class' => 'home-input'  // Clase CSS para el segundo input
                ],
                'label_attr' => [
                    'class' => 'home-label'  // Clase CSS para el segundo label
                ]
            ]
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
