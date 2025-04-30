<?php

namespace App\Form;

use App\Entity\Aventura;
use App\Entity\Resultado;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ResultadoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('puntos')
            ->add('fecha', null, [
                'widget' => 'single_text',
            ])
            ->add('nombre_publico')
            ->add('id_usuario', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'id',
            ])
            ->add('id_aventura', EntityType::class, [
                'class' => Aventura::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Resultado::class,
        ]);
    }
}
