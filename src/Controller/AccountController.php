<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountController extends AbstractController
{
    #[Route('/account', name: 'app_account')]
    public function index(): Response
    {
        $identity = $this->getUser()->getIdentity();
        // dd($identity);
        return $this->render('account/index.html.twig', [
            'identity' => $identity,
        ]);
    }
}
