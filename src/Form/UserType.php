<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Rol;
use App\Entity\Persona;
use App\Form\PersonaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', null, [
                'attr' => ['class' => 'home-input'],
                'label_attr' => ['class' => 'home-label'],
                'constraints' => [
                    new NotBlank(['message' => 'El correo es obligatorio.']),
                    new Email(['message' => 'Ingrese un correo válido.']),
                ],
            ])
           ->add('roles', EntityType::class, [
                'class' => Rol::class,
                'choice_label' => 'nombre',
                'multiple' => true,
                'expanded' => false,
                'by_reference' => false,
                'property_path' => 'rolesCollection', // para usar getRolesCollection() y setRolesCollection()
            ])

           ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'required' => false,
                'invalid_message' => 'Las contraseñas no coinciden.',
                'first_options' => [
                    'label' => '* Password',
                    'attr' => ['class' => 'home-input'],
                    'label_attr' => ['class' => 'home-label'],
                    'constraints' => [
                        new NotBlank(['message' => 'La contraseña no puede estar vacía.']),
                        new \Symfony\Component\Validator\Constraints\Length([
                            'min' => 6,
                            'minMessage' => 'La contraseña debe tener al menos {{ limit }} caracteres.',
                            'max' => 4096,
                        ]),
                    ],
                ],
                'second_options' => [
                    'label' => '* Repetir Password',
                    'attr' => ['class' => 'home-input'],
                    'label_attr' => ['class' => 'home-label'],
                ],
            ])

           ->add('persona', EntityType::class, [
                    'class' => Persona::class,
                    'choice_label' => function (Persona $persona) {
                        // Por ejemplo, muestra el nombre completo u otro campo
                        return $persona->getNombre() . ' ' . $persona->getApellido();
                    },
                    'placeholder' => 'Seleccione una persona',
                    'required' => false,
                    'attr' => ['class' => 'home-input'],
                    'label_attr' => ['class' => 'home-label'],
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
