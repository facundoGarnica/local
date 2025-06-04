<?php

namespace App\Form;

use App\Entity\ExamenFinal;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ExamenFinalType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('fecha')
            ->add('presidente_id')
            ->add('Vocal1_id')
            ->add('Vocal2_id')
            ->add('asignatura_id')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ExamenFinal::class,
        ]);
    }
}
