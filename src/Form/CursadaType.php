<?php

namespace App\Form;

use App\Entity\Cursada;
use App\Entity\Asignatura;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\Entity\Modalidad;

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
            'data' => 'regular',
            'label' => 'Condición'
        ])
        ->add('alumno')
        ->add('modalidad', EntityType::class, [
            'class' => Modalidad::class,
            'choice_label' => 'descripcion', // Ahora usas el campo 'descripcion'
            'label' => 'Modalidad'
        ])
        ->add('nota_id')
        ->add('curso');
    ;



    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Cursada::class,
        ]);
    }
}
