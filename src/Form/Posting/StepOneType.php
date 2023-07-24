<?php

namespace App\Form\Posting;

use App\Entity\Sector;
use App\Entity\Posting;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class StepOneType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Intitulé du poste *'
            ])
            ->add('number', NumberType::class, [
                'label' => 'Nombre de personne à recruter *'
            ])
            ->add('sector', EntityType::class, [
                'label' => 'Secteur d\'activité *',
                'class' => Sector::class,
                'expanded' => true,
                'multiple' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Posting::class,
        ]);
    }
}
