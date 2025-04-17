<?php

namespace App\Form;

use App\Entity\Comision;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ComisionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('anio')
            ->add('comision')
            ->add('estado')
            ->add('turno')
            ->add('tecnicatura')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Comision::class,
        ]);
    }
}
