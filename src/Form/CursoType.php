<?php


namespace App\Form;

use App\Entity\Curso;
use App\Entity\Asignatura;
use App\Entity\Comision;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TimeType;


class CursoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        
        
            ->add('asignatura', EntityType::class, [
                'class' => Asignatura::class,
                'choice_label' => 'nombre', // Nombre del campo a mostrar en el select
                'placeholder' => 'Selecciona una asignatura',
                'required' => true,
            ])
            ->add('comision', EntityType::class, [
                'class' => Comision::class,
                'choice_label' => 'comision', // Ajusta según tu campo
                'placeholder' => 'Selecciona una comision',
                'expanded' => false,
            ])
            ->add('CUPOF')

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Curso::class,
        ]);
    }
}



