<?php

namespace App\Form;

use App\Entity\AIcores;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\Autocomplete\Form\AsEntityAutocompleteField;
use Symfony\UX\Autocomplete\Form\ParentEntityAutocompleteType;

#[AsEntityAutocompleteField]
class AiAutocompleteField extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'class' => AIcores::class,
            'attr' => [
                'data-controller' => 'custom-autocomplete',
            ],
            'choice_label' => 'name',
            'placeholder' => 'Select your favorite AI tools',
            'multiple' => true,
        ]);
    }

    public function getParent(): string
    {
        return ParentEntityAutocompleteType::class;
    }
}
