<?php

namespace App\Form;

use App\Entity\AIcores;
use App\Entity\Identity;
use App\Entity\TechnicalSkill;
use App\Form\AiAutocompleteField;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use App\Form\TechSkillAutocompleteField;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use App\Form\DataTransformer\AiTransformer;
use App\Form\DataTransformer\SkillsTransformer;
use Symfony\Component\Form\CallbackTransformer;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\String\Slugger\SluggerInterface;
use App\Form\DataTransformer\StringToAIcoresTransformer;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class IaType extends AbstractType
{
    public function __construct(
        private AiTransformer $transformer,
        private SkillsTransformer $skillsTransformer,
        private EntityManagerInterface $entityManager,
        private SluggerInterface $sluggerInterface,
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // ->add('technicalSkills', TechSkillAutocompleteField::class, [
            //     'label' => false
            // ])
            ->add('technicalSkills', TextType::class, [
                'autocomplete' => true,
                'attr' => [
                    'data-controller' => 'technical-add-autocomplete',
                    'palcehoder' => "Domaine d'expertise",
                ],
                'tom_select_options' => [
                    'create' => true,
                    'createOnBlur' => true,
                    'delimiter' => ',',
                ],
                'autocomplete_url' => '/autocomplete/tech_skill_autocomplete_field' ,
                'no_results_found_text' => 'Aucun résultat' ,
                'no_more_results_text' => 'Plus de résultats' ,
            ])
            ->add('aicores', TextType::class, [
                'autocomplete' => true,
                'attr' => [
                    'data-controller' => 'custom-autocomplete',
                ],
                'tom_select_options' => [
                    'create' => true,
                    'createOnBlur' => true,
                    'delimiter' => ',',
                ],
                'autocomplete_url' => '/autocomplete/ai_autocomplete_field' ,
                'no_results_found_text' => 'Aucun résultat' ,
                'no_more_results_text' => 'Plus de résultats' ,
            ])
        ;

        $builder->get('aicores')
            ->addModelTransformer($this->transformer)
        ;

        $builder->get('technicalSkills')
            ->addModelTransformer($this->skillsTransformer)
        ;

        $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
            $data = $event->getData();
            $form = $event->getForm();
        
            // récupérer la valeur du champ "aicores" depuis le formulaire
            $aicoresDataValue = $form->get('aicores')->getNormData();
            $technicalSkillsDataValue = $form->get('technicalSkills')->getNormData();
            dump($technicalSkillsDataValue);
            
            // diviser la chaîne en tableau
            $values = explode(',', $aicoresDataValue);
            $skillValues = explode(',', $technicalSkillsDataValue);
            
                // trier les valeurs en IDs et chaînes de caractères
                list($ids, $strings) = $this->sortValue($values);
                list($skillsIds, $skillsStrings) = $this->sortValue($skillValues);
        
            // vider la collection originale
            foreach ($data->getAicores() as $existingAicore) {
                $data->removeAicore($existingAicore);
            }

            // vider la collection originale
            foreach ($data->getTechnicalSkills() as $existingSkill) {
                $data->removeTechnicalSkill($existingSkill);
            }
        
            // ajouter les nouvelles entités à partir des IDs
            foreach ($ids as $id) {
                $aicore = $this->entityManager->getRepository(AIcores::class)->find($id);
                if ($aicore !== null) {
                    $data->addAicore($aicore);
                }
            }

            // ajouter les nouvelles entités à partir des IDs
            foreach ($skillsIds as $id) {
                $skill = $this->entityManager->getRepository(TechnicalSkill::class)->find($id);
                if ($skill !== null) {
                    $data->addTechnicalSkill($skill);
                }
            }
        
            // créer et ajouter de nouvelles entités à partir des chaînes
            foreach ($strings as $string) {
                $aicore = $this->entityManager->getRepository(AIcores::class)->findOneBy([
                    'name' => $string
                ]);
                if ($aicore !== null) {
                    $data->addAicore($aicore);
                }
            }
        
            // créer et ajouter de nouvelles entités à partir des chaînes
            foreach ($skillsStrings as $string) {
                $skill = $this->entityManager->getRepository(TechnicalSkill::class)->findOneBy([
                    'name' => $string
                ]);
                if ($skill !== null) {
                    $data->addTechnicalSkill($skill);
                }
            }
        
            // mettre à jour les données de l'événement
            $event->setData($data);
        });
        
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Identity::class,
        ]);
    }

    private function sortValue($values)
    {
        $ids = [];
        $strings = [];
        foreach ($values as $value) {
            if (is_numeric($value)) {
                $ids[] = (int) $value;
            } else {
                $strings[] = $value;
            }
        }
        return [$ids, $strings];
    }
}
