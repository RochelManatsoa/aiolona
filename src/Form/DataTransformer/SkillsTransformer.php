<?php

namespace App\Form\DataTransformer;

use App\Entity\TechnicalSkill;
use App\Service\User\UserService;
use App\Service\Mailer\MailerService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class SkillsTransformer implements DataTransformerInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager, 
        private SluggerInterface $sluggerInterface,
        private MailerService $mailerService,
        private UserService $userService,
        )
    {
        $this->entityManager = $entityManager;
        $this->sluggerInterface = $sluggerInterface;
        $this->mailerService = $mailerService;
        $this->userService = $userService;        
    }

    public function transform($technicalSkills)
    {
        // dd(count($technicalSkills));
        if (null === $technicalSkills) {
            return '';
        }
        
        // Convertir en tableau si c'est une PersistentCollection
        if ($technicalSkills instanceof \Doctrine\ORM\PersistentCollection) {
            $technicalSkills = $technicalSkills->toArray();
        }

        return implode(',', array_map(function(TechnicalSkill $technicalSkill) {
            return $technicalSkill->getName();
        }, $technicalSkills));
    }

    public function reverseTransform($technicalSkillsString)
    {
        if ('' === $technicalSkillsString || null === $technicalSkillsString) {
            return new ArrayCollection(); // ou null, selon votre cas
        }
        // Convertissez la chaîne de caractères en une collection d'objets TechnicalSkill
        $technicalSkillsArray = explode(',', $technicalSkillsString);
        $technicalSkillsCollection = new ArrayCollection();

        foreach ($technicalSkillsArray as $technicalSkillId) {
            $technicalSkill = $this->entityManager->getRepository(TechnicalSkill::class)->findOneBy([
                'name' => $technicalSkillId
            ]);
            if (is_numeric($technicalSkillId)) {
                $technicalSkill = $this->entityManager->getRepository(TechnicalSkill::class)->find($technicalSkillId);
            } else {
                // Trouvez ou créez une entité basée sur la valeur textuelle
                $technicalSkill = $this->entityManager->getRepository(TechnicalSkill::class)->findOneBy([
                    'name' => $technicalSkillId
                ]);
                if(!$technicalSkill instanceof TechnicalSkill){
                    $technicalSkill = new TechnicalSkill();
                    $technicalSkill
                        ->setName($technicalSkillId)
                        ->setType('user')
                        ->setUrl('')
                        ->setSlug($this->sluggerInterface->slug($technicalSkillId))
                    ; 
                    // ou toute autre opération pour initialiser l'entité
                    $this->entityManager->persist($technicalSkill);
                    $this->entityManager->flush();
                }
            }
            if ($technicalSkill instanceof TechnicalSkill) {
                $technicalSkillsCollection->add($technicalSkill);
            }
        }
        dump($technicalSkillsCollection);

        return $technicalSkillsCollection;
    }
}
