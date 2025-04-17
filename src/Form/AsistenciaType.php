<?php

namespace App\Form;

use App\Entity\Asistencia;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class AsistenciaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /*$builder
            ->add('fecha')
            ->add('asistencia')
            ->add('observacion')
            ->add('cursada')
        ;*/
        $builder
            ->add('fecha', null, [
                'label' => 'Fecha',
                'label_attr' => [
                    'id' => 'label-fecha'  // Especifica el id del label aquí
                ],
                'attr' => [
                    'id' => 'input-fecha'  // Especifica el id del input aquí si es necesario
                ]
            ])
            ->add('asistencia', ChoiceType::class, [
                'choices' => [
                    'Presente' => 'presente',
                    'Ausente' => 'ausente',
                    'Media Falta' => 'mediafalta',
                    'Justificada' => 'justificada',

                ],
                'expanded' => false,  // Usa radio buttons en lugar de un desplegable
                'multiple' => false, // Asegura que solo una opción pueda ser seleccionada
                'label' => 'Asistencia',
            ])
            ->add('observacion')
            ->add('cursada')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Asistencia::class,
        ]);
    }
}
