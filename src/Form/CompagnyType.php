<?php

namespace App\Form;

use App\Entity\Compagny;
use Symfony\Component\Form\AbstractType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class CompagnyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, ['label' => 'company.name',])
            ->add('size', ChoiceType::class, [
                'choices' => Compagny::CHOICE_SIZE,
                'label' => 'Your company\'s number of employees',
                'required' => true,
                ])
            ->add('description', CKEditorType::class, [
                'config' => array('toolbar' => 'basic'),
                'label' => 'Description of your company *',
                'required' => true,
                'attr' => [
                    'rows' => 8
                ]
            ])
            ->add('website', TextType::class, ['label' => 'company.url'])
            ->add('country', CountryType::class, [
                'label' => 'company.country',
                'required' => false,
                'placeholder' => 'company.select.country',
            ])
            ->add('email', EmailType::class, [
                'label' => 'company.email',
                'required' => false,
            ])
            ->add('phone', TextType::class, ['label' => 'company.phone']);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Compagny::class,
        ]);
    }
}
