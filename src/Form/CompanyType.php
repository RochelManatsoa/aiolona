<?php

namespace App\Form;

use App\Entity\Sector;
use App\Entity\Compagny;
use App\Entity\TypePosting;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class CompanyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, ['label' => 'app_identity_company.name',])
            ->add('sector', EntityType::class, [
                'label' => 'app_identity_company.sector',
                'class' => Sector::class,
                'expanded' => true,
                'multiple' => true,
            ])
            ->add('size', ChoiceType::class, [
                'choices' => Compagny::CHOICE_SIZE,
                'label' => 'app_identity_company.size',
                'required' => true,
                ])
            ->add('country', CountryType::class, [
                'label' => 'app_identity_company.country',
                'required' => false,
                'placeholder' => 'company.select.country',
            ])
            ->add('typeSearch', EntityType::class, [
                'label' => 'app_identity_company.type_posting',
                'class' => TypePosting::class,
                'expanded' => true,
                'multiple' => true,
            ])
            ->add('description', TextareaType::class, [
                'label' => 'app_identity_company.desc',
                'required' => true,
                'attr' => [
                    'rows' => 8
                ]
            ])
            ->add('budget', TextType::class, ['label' => 'app_identity_company.budget'])
            ->add('project', TextareaType::class, [
                'label' => 'app_identity_company.desc',
                'required' => true,
                'attr' => [
                    'rows' => 8
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => 'app_identity_company.email',
                'required' => false,
            ])
            ->add('phone', TextType::class, ['label' => 'app_identity_company.phone'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Compagny::class,
        ]);
    }
}
