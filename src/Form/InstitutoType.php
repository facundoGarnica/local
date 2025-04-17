<?php

namespace App\Form;

use App\Entity\Instituto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InstitutoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nombre')
            ->add('numero')
            ->add('url_instituto')
            ->add('tipo')
            ->add('email')
            ->add('calle')
            ->add('altura')
            ->add('numero_cue')
            ->add('instituto')
            ->add('localidad')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Instituto::class,
        ]);
    }
}
