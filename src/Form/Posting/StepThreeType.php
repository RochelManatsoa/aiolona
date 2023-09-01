<?php

namespace App\Form\Posting;

use App\Entity\AIcores;
use App\Entity\Posting;
use App\Form\AiAutocompleteField;
use Symfony\Component\Form\AbstractType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class StepThreeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('skills', EntityType::class, [
                'label' => false,
                'class' => AIcores::class,
                'autocomplete' => true,
                'multiple' => true,
                'attr' => [
                    'placeholder' => 'AI Tools',
                    'data-controller' => 'experience-autocomplete',
                ]
            ])
            ->add('desctiption', CKEditorType::class, [
                'config' => array('toolbar' => 'basic'),
                'label' => 'Description du poste *',
                'required' => true,
                'attr' => [
                    'rows' => 8
                ]
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
