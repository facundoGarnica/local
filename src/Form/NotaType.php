<?php

namespace App\Form;

use App\Entity\Nota;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class NotaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('parcial', NumberType::class, [
                'scale' => 1,
                'html5' => true,
                'attr' => [
                    'step' => '0.1',
                    'min' => 1,
                    'max' => 10,
                ],
            ])
            ->add('recuperatorio1', NumberType::class, [
                'scale' => 1,
                'html5' => true,
                'attr' => [
                    'step' => '0.1',
                    'min' => 1,
                    'max' => 10,
                ],
            ])
            ->add('parcial2', NumberType::class, [
                'scale' => 1,
                'html5' => true,
                'attr' => [
                    'step' => '0.1',
                    'min' => 1,
                    'max' => 10,
                ],
            ])
            ->add('recuperatorio2', NumberType::class, [
                'scale' => 1,
                'html5' => true,
                'attr' => [
                    'step' => '0.1',
                    'min' => 1,
                    'max' => 10,
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Nota::class,
        ]);
    }
}
