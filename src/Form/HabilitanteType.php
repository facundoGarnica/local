<?php

namespace App\Form;

use App\Entity\Habilitante;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HabilitanteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('anio_egreso')
            ->add('promedio')
            ->add('titulo')
            ->add('docente')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Habilitante::class,
        ]);
    }
}
