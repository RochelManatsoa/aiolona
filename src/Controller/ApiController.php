<?php

namespace App\Controller;

use App\Entity\AIcores;
use App\Entity\Identity;
use App\Repository\IdentityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
    #[Route('/api/experts', name: 'api_experts', methods: ['GET'])]
    public function index(IdentityRepository $repository): Response
    {
        return $this->json($repository->findAllValid(), 200, ["Content-Type" => "application/json"], ['groups' => 'identity']);
    }

    #[Route('/api/expert/{username}', name: 'api_expert', methods: ['GET'])]
    public function getOneOrNull(IdentityRepository $repository, Identity $identity): Response
    {
        return $this->json($repository->findBy(['username' => $identity->getUsername()]), 200, ["Content-Type" => "application/json"], ['groups' => 'identity']);
    }

    #[Route('/api/experts/{slug}/tools', name: 'api_expert_ai', methods: ['GET'])]
    public function getIdentityByIa(IdentityRepository $repository, AIcores $aIcores): Response
    {
        return $this->json($repository->getIdentityByIa($aIcores->getSlug()), 200, ["Content-Type" => "application/json"], ['groups' => 'identity']);
    }
}
