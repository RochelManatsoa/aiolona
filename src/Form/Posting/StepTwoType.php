<?php

namespace App\Form\Posting;

use App\Entity\Posting;
use App\Entity\SchedulePosting;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StepTwoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('tarif', NumberType::class, [
                'label' => 'Rate per hour *',
                'required' => true
            ])
            ->add('typePosting')
            ->add('schedulePostings', EntityType::class, [
                'label' => 'Type d\'horaire ',
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
