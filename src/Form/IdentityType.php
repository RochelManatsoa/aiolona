<?php

namespace App\Form;

use App\Entity\Sector;
use App\Entity\Account;
use App\Entity\AIcores;
use App\Entity\Identity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CountryType;

class IdentityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName')
            ->add('lastName')
            ->add('username')
            ->add('bio')
            ->add('aicores', EntityType::class, [
                'class' => AIcores::class, 
                'choice_label' => 'name', 
                'multiple' => true, // Si vous souhaitez permettre la sélection multiple
                'expanded' => true, // Si vous souhaitez afficher les options comme des cases à cocher plutôt qu'un select
            ])
            // ->add('aicores')
            // ->add('user', UserType::class, [])
            ->add('country', CountryType::class, [
                'label' => 'Pays',
                'required' => false,
                'placeholder' => 'Sélectionnez un pays',
            ])
            ->add('sectors', EntityType::class, [
                'class' => Sector::class, 
                'choice_label' => 'name', 
                'label' => 'Sélectionnez votre secteur',
                'multiple' => true, // Si vous souhaitez permettre la sélection multiple
                'expanded' => true, // Si vous souhaitez afficher les options comme des cases à cocher plutôt qu'un select
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Identity::class,
        ]);
    }
}
