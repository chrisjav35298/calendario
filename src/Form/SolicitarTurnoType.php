<?php

namespace App\Form;

use App\Entity\SolicitarTurno;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class SolicitarTurnoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('solicitante')
            ->add('fecha', DateType::class, [
                'widget' => 'single_text',
            ])
            ->add('observacion', TextareaType::class, [
                'required' => false,
                'attr' => ['class' => ''], 
            ])
            ->add('turno', ChoiceType::class, [
                'choices' => [
                    '8 a 10' => 'morning_1',
                    '10 a 12' => 'morning_2',
                    '14 a 18' => 'tarde_1',
                    '18 a 20' => 'tarde_2',
                   // 'Indistinto' => 3,
                ]
            ])
            ->add('estado', ChoiceType::class, [
                'choices' => [
                    'Enviado' => 1,
                    'Confirmado' => 2,
                    'Rechazado' => 3,
                ],
                // 'attr' => [
                //     'class' => 'hidden' // esta oculto para el usuario que solicita el turno
                // ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SolicitarTurno::class,
        ]);
    }
}
