<?php

namespace App\Form;

use App\Entity\Gado;
use App\Entity\Fazenda;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GadoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('codigo', TextType::class, [
                'label' => 'Código',
            ])
            ->add('leite', NumberType::class, [
                'label' => 'Leite (litros por semana)',
                'scale' => 2,
            ])
            ->add('racao', NumberType::class, [
                'label' => 'Ração (kg por semana)',
                'scale' => 2,
            ])
            ->add('peso', NumberType::class, [
                'label' => 'Peso (kg)',
                'scale' => 2,
            ])
            ->add('dataNascimento', DateType::class, [
                'label' => 'Data de Nascimento',
                'widget' => 'single_text',
            ])
            ->add('fazenda', EntityType::class, [
                'class' => Fazenda::class,
                'choice_label' => 'nome',
                'label' => 'Fazenda',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Gado::class,
        ]);
    }
}
