<?php

namespace App\Form;

use App\Entity\Carreras;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class CarrerasType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('estado', ChoiceType::class, [
                'choices' => [
                    'No' => 0,
                    'Sí' => 1,
                ],
                'expanded' => true,  // radio buttons; si preferís un select, poné false
                'multiple' => false,
            ])
            ->add('inicio', DateType::class, [
                'label_attr' => ['id' => 'label_inicio_pre'],
                'widget' => 'choice',
            ])
            ->add('fin', DateType::class, [
                'label_attr' => ['id' => 'label_fin_pre'],
                'widget' => 'choice',
                'required' => false,
            ])
            ->add('estudiante_id')
            ->add('tecnicatura_id')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Carreras::class,
        ]);
    }
}
