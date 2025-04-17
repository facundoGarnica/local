<?php

namespace App\Form;

use App\Entity\Cursada;
use App\Entity\Asignatura;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class CursadaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
       /* $builder
            ->add('condicion')
            ->add('alumno')
            ->add('modalidad')
            ->add('nota_id')
            ->add('curso')
        ;*/
        $builder
        ->add('condicion', TextType::class, [
            'data' => 'regular', // Valor predeterminado
            'label' => 'Condición'
        ])
        ->add('alumno')
        ->add('modalidad', TextType::class, [
            'data' => 'presencial', // Valor predeterminado
            'label' => 'Modalidad'
        ])
        ->add('nota_id')
        ->add('curso')
    ;



    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Cursada::class,
        ]);
    }
}
