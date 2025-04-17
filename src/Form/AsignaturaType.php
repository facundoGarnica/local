<?php

namespace App\Form;

use App\Entity\Asignatura;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class AsignaturaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nombre')
            ->add('anio')
            ->add('programa')
            ->add('cant_mod')
            //->add('duracion')
            ->add('duracion', ChoiceType::class, [
                'choices' => [
                    'Anual' => 'Anual',
                    '1er Cuatrimestre' => '1er Cuatrimestre',
                    '2do Cuatrimestre' => '2do Cuatrimestre',
                ],
                'expanded' => false,  // Usa radio buttons en lugar de un desplegable
                'multiple' => false, // Asegura que solo una opción pueda ser seleccionada
                'label' => 'Duracion',
            ])
            ->add('tecnicatura')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Asignatura::class,
        ]);
    }
}
