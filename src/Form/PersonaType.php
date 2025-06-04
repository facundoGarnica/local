<?php

namespace App\Form;

use App\Entity\Persona;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType; 
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\NotBlank;

class PersonaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nombre')
            ->add('apellido')
            ->add('fecha_nacimiento', DateType::class, [
                'label_attr' => ['id' => 'label_fecha_persona_form'],
                'widget' => 'choice', 
                'format' => 'dd-MM-yyyy',
                'attr' => [
                    'class' => 'datepicker',
                ],
                'years' => range(2025, 2025 - 100), // De 2025 a 1925
            ])
            ->add('dni_pasaporte')
            ->add('genero', ChoiceType::class, [
                'choices' => [
                    'Otro' => 'otro',
                    'Masculino' => 'masculino',
                    'Femenino' => 'femenino',
                ],
                'placeholder' => 'Seleccione una opción', // Opción por defecto
                'expanded' => false, 
            ])
            ->add('email')
            
            
            
            ->add('telefono', TelType::class, [
                'required' => false,
                'attr' => [
                    'placeholder' => '+54 11 1234-5678',
                ],
                'constraints' => [
                    new Regex([
                        'pattern' => '/^\+\d{1,3}\s?\d{1,4}\s?\d{4,}$/',
                        'message' => 'El número debe tener formato internacional, por ejemplo: +54 11 12345678',
                    ]),
                ],
            ])
            ->add('partido')
            ->add('calle')
            ->add('numero')
            ->add('piso')
            ->add('departamento')
            ->add('pasillo')
            ->add('pais')
            ->add('localidad')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Persona::class,
        ]);
    }
}
