<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nombre_completo')
            ->add('apodo')
            ->add('genero')
            ->add('fecha_nacimiento')
            ->add('vive_palmilla')
            ->add('email')
            ->add('role')
            ->add('creado_en', null, [
                'widget' => 'single_text',
            ])
            ->add('actualizado_en', null, [
                'widget' => 'single_text',
            ])
            ->add('telefono')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
