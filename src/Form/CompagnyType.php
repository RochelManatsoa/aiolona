<?php

namespace App\Form;

use App\Entity\Compagny;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CompagnyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('size')
            ->add('description', TextareaType::class,[
                'attr' => [
                    'rows' => 6
                ]
            ])
            ->add('website')
            ->add('country', CountryType::class, [
                'label' => 'Pays',
                'required' => false,
                'placeholder' => 'Sélectionnez un pays',
            ])
            ->add('email', EmailType::class, [
                'label' => 'Adresse e-mail professionnelle ',
                'required' => false,
            ])
            ->add('phone')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Compagny::class,
        ]);
    }
}
