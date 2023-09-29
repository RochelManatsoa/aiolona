<?php

namespace App\Form;

use App\Entity\Expert;
use App\Entity\Sector;
use App\Entity\TypePosting;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ExpertType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, ['label' => 'app_identity_expert.name',])
            ->add('sector', EntityType::class, [
                'label' => 'app_identity_expert.sector',
                'class' => Sector::class,
                'expanded' => true,
                'multiple' => true,
            ])
            ->add('years', ChoiceType::class, [
                'choices' => Expert::CHOICE_YEAR,
                'label' => 'app_identity_expert.year',
                'required' => true,
                ])
            ->add('localisation', CountryType::class, [
                'label' => 'app_identity_expert.localisation',
                'required' => false,
                'placeholder' => 'company.select.country',
            ])
            ->add('mainSkills', TextareaType::class, [
                'label' => 'app_identity_expert.main_skills',
                'required' => true,
                'attr' => [
                    'rows' => 8
                ]
            ])
            ->add('aspiration', TextareaType::class, [
                'label' => 'app_identity_expert.aspiration',
                'required' => true,
                'attr' => [
                    'rows' => 8
                ]
            ])
            ->add('preference', TextareaType::class, [
                'label' => 'app_identity_expert.preference',
                'required' => true,
                'attr' => [
                    'rows' => 8
                ]
            ])
            ->add('website')
            ->add('typeJob', EntityType::class, [
                'label' => 'app_identity_expert.type_job',
                'class' => TypePosting::class,
                'expanded' => true,
                'multiple' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Expert::class,
        ]);
    }
}
