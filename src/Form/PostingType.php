<?php

namespace App\Form;

use App\Entity\Posting;
use App\Entity\SchedulePosting;
use Symfony\Component\Form\AbstractType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('desctiption', CKEditorType::class, [
                'config' => array('toolbar' => 'basic'),
                'label' => 'Description du poste *',
                'required' => true,
                'attr' => [
                    'rows' => 8
                ]
            ])
            ->add('tarif')
            ->add('number')
            ->add('sector')
            ->add('typePosting')
            ->add('schedulePosting', EntityType::class, [
                'label' => 'Type d\'horaire',
                'class' => SchedulePosting::class,
                'expanded' => true,
                'multiple' => true,
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
