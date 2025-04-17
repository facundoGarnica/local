<?php

namespace App\Form;

use App\Entity\Correlativa;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Asignatura;

class CorrelativaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('motivo')
        ->add('asignatura', EntityType::class, [
            'class' => Asignatura::class,
            'choice_label' => 'nombre',
            'label' => 'Asignatura Principal',  // Cambié el label para reflejar el campo correcto
        ])
        ->add('correlativa', EntityType::class, [
            'class' => Asignatura::class,
            'choice_label' => 'nombre',
            'label' => 'Asignatura Correlativa',
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Correlativa::class,
        ]);
    }
}
