<?php

namespace App\Form\Search;

use App\Entity\Sector;
use App\Data\SeachData;
use App\Entity\Lang;
use App\Form\AiAutocompleteField;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class SearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('q', TextType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'Rechercher'
                ]
            ])
            ->add('sectors', EntityType::class, [
                'label' => false,
                'required' => false,
                'class' => Sector::class,
                'expanded' => true,
                'multiple' => true,
            ])
            ->add('langues', EntityType::class, [
                'label' => false,
                'required' => false,
                'class' => Lang::class,
                'expanded' => true,
                'multiple' => true,
            ])
            ->add('aicores', AiAutocompleteField::class, [
                'label' => false,
                'required' => false,
            ])
            ->add('max', NumberType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'Max'
                ]
            ])
            ->add('min', NumberType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'Min'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SeachData::class,
            'method' => 'GET',
            'csrf_=>protection' => false
        ]);
    }
    
    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return '';
    }
}