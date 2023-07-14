<?php

namespace App\Form;

use App\Entity\Lang;
use App\Entity\Language;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class LanguageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('lang', EntityType::class, [
                'class' => Lang::class,
                'choice_label' => 'name',
                'autocomplete' => true
            ])
            ->add('level', ChoiceType::class, [
                'choices' => Language::CHOICE_LEVEL
            ])
        ;
        
        $builder->addEventListener(
            FormEvents::SUBMIT,
            function (FormEvent $event): void {

                $data = $event->getData();
                $lang = $data->getLang()->getName();
                $code = $data->getLang()->getCode();
                
                $data->setTitle($lang);
                $data->setCode($code);
            }
        );

        if(!$options['edit']){

            $builder->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800',
                ]
            ])
            ;
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Language::class,
            'edit' => false,
        ]);

        $resolver->setAllowedTypes('edit', 'bool');
    }
}
