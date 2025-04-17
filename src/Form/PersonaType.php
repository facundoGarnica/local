<?php

namespace App\Form;

use App\Entity\Persona;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType; 

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
                'years' => range(date('Y') - 120, date('Y'), 1), // Rango de 120 años atrás hasta el año actual
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
            ->add('telefono')
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
