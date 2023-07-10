<?php

namespace App\Controller;

use App\Entity\Identity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountController extends AbstractController
{
    #[Route('/account', name: 'app_account')]
    public function index(): Response
    {
        $identity = $this->getUser()->getIdentity();
        if($identity instanceof Identity){
            // dd($identity);
            return $this->render('account/index.html.twig', [
                'identity' => $identity,
            ]);
        }
        return $this->redirectToRoute('app_profile');
    }
}
