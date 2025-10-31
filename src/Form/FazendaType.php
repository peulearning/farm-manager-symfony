<?php

namespace App\Form;

use App\Entity\Fazenda;
use App\Entity\Veterinario;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FazendaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nome', TextType::class, [
                'label' => 'Nome da Fazenda',
            ])
            ->add('tamanho', NumberType::class, [
                'label' => 'Tamanho (ha)',
                'scale' => 2,
            ])
            ->add('responsavel', TextType::class, [
                'label' => 'Responsável',
            ])
            ->add('veterinarios', EntityType::class, [
                'class' => Veterinario::class,
                'choice_label' => 'nome',
                'multiple' => true,
                'required' => false,
                'label' => 'Veterinários',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Fazenda::class,
        ]);
    }
}
