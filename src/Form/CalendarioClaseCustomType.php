<?php

namespace App\Form;

use App\Entity\CalendarioClase;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CalendarioClaseCustomType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // Por ejemplo: solo modalidad y curso si es una versión "rápida"
        $builder
            ->add('modalidad')
            ->add('curso')
            ->add('observacion', null, [
                'required' => false,   // para que pueda ser null
            ])
        ;

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CalendarioClase::class,
            
        ]);
        
    }
}
