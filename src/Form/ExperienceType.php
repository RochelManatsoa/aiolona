<?php

namespace App\Form;

use App\Entity\AIcores;
use App\Entity\Experience;
use App\Entity\TechnicalSkill;
use Symfony\Component\Form\AbstractType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ExperienceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Title *',
                    'class' => "border w-full rounded p-2 m-1"
                ]
            ])
            ->add('company', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Company *',
                    'class' => "border w-full rounded p-2 m-1"
                ]
            ])
            ->add('currently', CheckboxType::class, [
                'label' => 'Currenty'
            ])
            ->add('startDate', DateType::class, [
                'html5' => false,
                'widget' => 'choice',
                'label' => false,
                'attr' => [
                    'placeholder' => 'Start',
                    'class' => "flex w-full rounded p-2 m-1"
                ],
                'data' => (new \DateTime('now'))->modify('-20 years'),
            ])
            ->add('endDate', DateType::class, [
                'html5' => false,
                'widget' => 'choice',
                'label' => false,
                'attr' => [
                    'placeholder' => 'End',
                    'class' => "flex w-full rounded p-2 m-1"
                ],
                'data' => new \DateTime('now')
            ])
            ->add('description', CKEditorType::class, [
                'config' => array('toolbar' => 'basic'),
                'label' => 'Description *',
                'required' => true,
                'attr' => [
                    'rows' => 8
                ]
            ])
            ->add('skills', EntityType::class, [
                'label' => false,
                'class' => AIcores::class,
                'autocomplete' => true,
                'multiple' => true,
                'required' => false,
                'attr' => [
                    'placeholder' => 'AI Tools',
                    'data-controller' => 'experience-autocomplete',
                ]
            ])
            ->add('technicalSkills', EntityType::class, [
                'label' => false,
                'class' => TechnicalSkill::class,
                'autocomplete' => true,
                'multiple' => true,
                'required' => false,
                'attr' => [
                    'placeholder' => 'Skill Tools',
                    'data-controller' => 'default-autocomplete',
                ]
            ])
        ;

        if(!$options['edit']){

            $builder->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800',
                ]
            ])
            ;
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Experience::class,
            'edit' => false,
        ]);

        $resolver->setAllowedTypes('edit', 'bool');
    }
}
