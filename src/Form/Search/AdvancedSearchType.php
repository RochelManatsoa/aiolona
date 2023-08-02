<?php

namespace App\Form\Search;

use App\Entity\Sector;
use App\Data\SeachData;
use App\Entity\AIcores;
use App\Entity\Lang;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class AdvancedSearchType extends AbstractType
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
                'label' => 'Sector',
                'required' => false,
                'class' => Sector::class,
                'autocomplete' => true,
                'placeholder' => 'Select language',
                'multiple' => true,
            ])
            ->add('langues', EntityType::class, [
                'label' => 'Language',
                'class' => Lang::class,
                'choice_label' => 'name',
                'required' => false,
                'autocomplete' => true,
                'placeholder' => 'Select language',
                'multiple' => true,
            ])
            ->add('aicores', EntityType::class, [
                'label' => 'AI Tools',
                'class' => AIcores::class,
                'choice_label' => 'name',
                'required' => false,
                'autocomplete' => true,
                'placeholder' => 'Select AI Tools',
                'multiple' => true,
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