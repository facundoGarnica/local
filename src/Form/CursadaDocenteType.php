<?php

namespace App\Form;

use App\Entity\CursadaDocente;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


class CursadaDocenteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('toma')
            ->add('cese')
            ->add('docente')
            ->add('revista')
            ->add('licencia', ChoiceType::class, [
                'label' => '¿Tiene licencia?',
                'choices'  => [
                    'Sí' => true,
                    'No' => false,
                ],
                'expanded' => true, // para mostrarlo como radios, poner false si preferís un select
                'multiple' => false,
            ])
            
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
