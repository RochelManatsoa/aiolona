<?php

namespace App\Form;

use App\Entity\Posting;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('desctiption')
            ->add('tarif')
            ->add('number')
            ->add('sector')
            ->add('typePosting')
            ->add('honoraryPosting')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Posting::class,
        ]);
    }
}
