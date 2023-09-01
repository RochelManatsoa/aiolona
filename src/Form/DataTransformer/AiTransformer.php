<?php

namespace App\Form\DataTransformer;

use App\Entity\AIcores;
use App\Service\Mailer\MailerService;
use App\Service\User\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class AiTransformer implements DataTransformerInterface
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

    public function transform($aicores)
    {
        if (null === $aicores) {
            return '';
        }

        // Convertir en tableau si c'est une PersistentCollection
        if ($aicores instanceof \Doctrine\ORM\PersistentCollection) {
            $aicores = $aicores->toArray();
        }

        return implode(',', array_map(function(AIcores $aicore) {
            return $aicore->getName();
        }, $aicores));
    }

    public function reverseTransform($aicoreString)
    {
        if (!$aicoreString) {
            return [];
        }

        $aicoreIds = explode(',', $aicoreString);
        $aicores = [];

        foreach ($aicoreIds as $aicoreId) {
            $aicore = $this->entityManager->getRepository(AIcores::class)->findOneBy([
                'name' => $aicoreId
            ]);
            if (is_numeric($aicoreId)) {
                $aicore = $this->entityManager->getRepository(AIcores::class)->find($aicoreId);
            } else {
                // Trouvez ou créez une entité basée sur la valeur textuelle
                $aicore = $this->entityManager->getRepository(AIcores::class)->findOneBy([
                    'name' => $aicoreId
                ]);
                if(!$aicore instanceof AIcores){
                    $aicore = new AIcores();
                    $aicore
                        ->setName($aicoreId)
                        ->setType('user')
                        ->setUrl('')
                        ->setSlug($this->sluggerInterface->slug($aicoreId))
                    ; 
                    // ou toute autre opération pour initialiser l'entité
                    $this->entityManager->persist($aicore);
                    $this->entityManager->flush();
                    
                    // envoi email à Sahra pour la validation
                    $this->mailerService->send(
                        "miandrisoa.olona@gmail.com",
                        "Validation requise : Nouvel outil ajouté sur PostIn Expert",
                        "new_ia_email.html.twig",
                        [
                            'user' => $this->userService->getCurrentUser(),
                            'aicore' => $aicore,
                            'url' => 'https://postin-expert.com/admin',
                        ]
                    );
                }
            }
            $aicores[] = $aicore;
        }

        return $aicores;
    }
}
