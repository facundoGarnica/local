<?php

namespace App\Form;

use App\Entity\CursadaDocente;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CursadaDocenteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('toma')
            ->add('cese')
            ->add('docente')
            ->add('revista')
            ->add('licencia')
            ->add('curso')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CursadaDocente::class,
        ]);
    }
}
