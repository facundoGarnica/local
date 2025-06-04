<?php

namespace App\Form;

use App\Entity\Alumno;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
class AlumnoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $currentYear = (int) date('Y');
    $years = range($currentYear, $currentYear - 100);
    $builder
    ->add('titulo_sec', TextType::class, [
        'required' => true,
        'label' => 'Título secundario',
       
        'attr' => ['placeholder' => 'Ingrese el título obtenido'],
        'invalid_message' => 'Debe completar el título secundario.',
    ])
    ->add('escuela_sec', TextType::class, [
        'required' => true,
        'label' => 'Escuela secundaria',
        
        'attr' => ['placeholder' => 'Ingrese el nombre de la escuela'],
    ])
    ->add('anio_egreso', ChoiceType::class, [
        'required' => true,
        'label' => 'Año de egreso',
        'choices' => array_combine($years, $years),
        'placeholder' => 'Seleccione un año',
       
    ])
    ->add('persona', null, [
        'constraints' => [
            new NotBlank(['message' => 'Este campo no puede estar vacío.']),
        ],
    ]);
}

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Alumno::class,
        ]);
    }
}
