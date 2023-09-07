<?php

namespace App\Form;

use App\Entity\TechnicalSkill;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\Autocomplete\Form\AsEntityAutocompleteField;
use Symfony\UX\Autocomplete\Form\ParentEntityAutocompleteType;

#[AsEntityAutocompleteField]
class TechSkillAutocompleteField extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'class' => TechnicalSkill::class,
            'attr' => [
                'data-controller' => 'technical-add-autocomplete',
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
