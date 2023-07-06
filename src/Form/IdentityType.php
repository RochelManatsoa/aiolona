<?php

namespace App\Form;

use App\Entity\Sector;
use App\Entity\Account;
use App\Entity\AIcores;
use App\Entity\Identity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
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
            ->add('file', VichImageType::class, [])
            // ->add('aicores', TextType::class, [
            //     'autocomplete' => true,
            //     'tom_select_options' => [
            //         'create' => true,
            //         'createOnBlur' => true,
            //         'delimiter' => ',',
            //     ],
            //     'autocomplete_url' => 'ux_entity_autocomplete_entity',
            // ])
            ->add('aicores', AiAutocompleteField::class, [])
            ->add('tarif', MoneyType::class, [])
            // ->add('user', UserType::class, [])
            // ->add('country', CountryType::class, [
            //     'label' => 'Pays',
            //     'required' => false,
            //     'placeholder' => 'Sélectionnez un pays',
            // ])
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
