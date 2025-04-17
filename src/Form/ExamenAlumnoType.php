<?php

namespace App\Form;

use App\Entity\ExamenAlumno;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ExamenAlumnoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nota')
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
