<?php

namespace App\Form;

use App\Entity\Aventura;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AventuraType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titulo')
            ->add('descripcion')
            ->add('imagen_portada')
            ->add('numero_de_retos')
            ->add('creado_en', null, [
                'widget' => 'single_text',
            ])
            ->add('actualizado_en', null, [
                'widget' => 'single_text',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Aventura::class,
        ]);
    }
}
