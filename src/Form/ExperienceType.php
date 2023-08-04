<?php

namespace App\Form;

use App\Entity\AIcores;
use App\Entity\Experience;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
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
                ]
            ])
            ->add('endDate', DateType::class, [
                'html5' => false,
                'widget' => 'choice',
                'label' => false,
                'attr' => [
                    'placeholder' => 'End',
                    'class' => "flex w-full rounded p-2 m-1"
                ]
            ])
            ->add('description', TextareaType::class, [
                'label' => false,
                'attr' => [
                    'rows' => 6,
                    'placeholder' => 'Description',
                    'class' => "border w-full rounded p-2 m-1"
                ]
            ])
            ->add('location', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Location',
                    'class' => "border w-full rounded p-2 m-1"
                ]
            ])
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
            ->add('country', CountryType::class, [
                'label' => 'Pays',
                'required' => false,
                'placeholder' => 'Sélectionnez un pays',
                'attr' => [
                    'class' => 'peer block min-h-[auto] w-full rounded border-0 bg-transparent px-3 py-[0.32rem] leading-[1.6] outline-none transition-all duration-200 ease-linear focus:placeholder:opacity-100 data-[te-input-state-active]:placeholder:opacity-100 motion-reduce:transition-none dark:text-neutral-200 dark:placeholder:text-neutral-200 [&:not([data-te-input-placeholder-active])]:placeholder:opacity-0'
                ],
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
