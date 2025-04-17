<?php

namespace App\Form;

use App\Entity\Nota;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NotaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('parcial')
            ->add('recuperatorio1')
            ->add('parcial2')
            ->add('recuperatorio2')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Nota::class,
        ]);
    }
}
