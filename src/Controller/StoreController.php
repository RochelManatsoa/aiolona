<?php

namespace App\Controller;

use App\Repository\AIcoresRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StoreController extends AbstractController
{
    #[Route('/store', name: 'app_store')]
    public function index(
        AIcoresRepository $aIcoresRepository,
        Request $request,
    ): Response
    {
        $offset = $request->query->get('offset', 0);
        $aicores = $aIcoresRepository->findSearch('publish', 12, $offset);

        return $this->render('store/index.html.twig', [
            'aicores' => $aicores,
        ]);
    }
}
