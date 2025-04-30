<?php

namespace App\Form;

use App\Entity\Aventura;
use App\Entity\Reto;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RetoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titulo')
            ->add('descripcion')
            ->add('instrucciones')
            ->add('tipo_reto')
            ->add('imagen_reto')
            ->add('respuestas')
            ->add('respuesta_correcta')
            ->add('latitud')
            ->add('longitud')
            ->add('margen_error_metros')
            ->add('puntos_fallo_0')
            ->add('puntos_fallo_1')
            ->add('puntos_fallo_2')
            ->add('puntos_fallo_3')
            ->add('es_obligatorio_superar')
            ->add('creado', null, [
                'widget' => 'single_text',
            ])
            ->add('actualizado', null, [
                'widget' => 'single_text',
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
            'data_class' => Reto::class,
        ]);
    }
}
