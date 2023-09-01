<?php

namespace App\Form;

use App\Entity\AIcores;
use App\Entity\Identity;
use App\Form\AiAutocompleteField;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use App\Form\DataTransformer\AiTransformer;
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
        private EntityManagerInterface $entityManager,
        private SluggerInterface $sluggerInterface,
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // ->add('aicores', AiAutocompleteField::class, [
            //     'label' => false
            // ])
            ->add('aicores', TextType::class, [
                'autocomplete' => true,
                'attr' => [
                    'data-controller' => 'custom-add-autocomplete',
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

        $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
            $data = $event->getData();
            $form = $event->getForm();
        
            // récupérer la valeur du champ "aicores" depuis le formulaire
            $aicoresDataValue = $form->get('aicores')->getNormData();
            
            // diviser la chaîne en tableau
            $values = explode(',', $aicoresDataValue);
            
                // trier les valeurs en IDs et chaînes de caractères
                $ids = [];
                $strings = [];
                foreach ($values as $value) {
                    if (is_numeric($value)) {
                        $ids[] = (int) $value;
                    } else {
                        $strings[] = $value;
                    }
                }
        
            // vider la collection originale
            foreach ($data->getAicores() as $existingAicore) {
                $data->removeAicore($existingAicore);
            }
        
            // ajouter les nouvelles entités à partir des IDs
            foreach ($ids as $id) {
                $aicore = $this->entityManager->getRepository(AIcores::class)->find($id);
                if ($aicore !== null) {
                    $data->addAicore($aicore);
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
}
