<?php

namespace App\Form;

use App\Entity\ExamenAlumno;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\NumberType;


class ExamenAlumnoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('nota', NumberType::class, [
            'scale' => 1,
            'html5' => true,
            'attr' => [
                'step' => '0.1',
                'min' => 1,
                'max' => 10,
            ],
        ])
            ->add('tomo')
            ->add('folio')
            ->add('alumno_id')
            ->add('examenFinal_id')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ExamenAlumno::class,
        ]);
    }
}
