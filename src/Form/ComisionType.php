<?php

namespace App\Form;

use App\Entity\Comision;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ComisionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('anio', ChoiceType::class, [
            'choices' => [
                '1' => '1',
                '2' => '2',
                '3' => '3',
                '4' => '4',
                '5' => '5',
               
            ],
            'label' => 'Año',
        ])
            ->add('comision', ChoiceType::class, [
                'choices' => [
                    '1ra' => '1ra',
                    '2da' => '2da',
                    '3ra' => '3ra',
                    '4ta' => '4ta',
                    '5ta' => '5ta',
                    '6ta' => '6ta',
                    '7ma' => '7ma',
                    '8va' => '8va',
                    '9na' => '9na',
                    '10ma' => '10ma',
                ],
                'label' => 'Comisión',
            ])
            ->add('estado', ChoiceType::class, [
                'choices' => [
                    'Activo' => true,
                    'Inactivo' => false,
                ],
                'expanded' => true, // opcional: muestra como radio buttons
                'multiple' => false,
            ])
            ->add('turno')
            ->add('tecnicatura')
            ->add('ciclo_lectivo', ChoiceType::class, [
            'choices' => array_combine(range(2020, 2026), range(2020, 2026)),
            'placeholder' => 'Selecciona un año',
            'required' => true,
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Comision::class,
        ]);
    }
}
