<?php

namespace App\Controller;

use App\Entity\AIcores;
use App\Manager\AiNoteManager;
use App\Repository\AINoteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AjaxController extends AbstractController
{
    #[Route('/ajax/{slug}/{star}', name: 'ajax_stars')]
    public function index(
        AIcores $aIcores, 
        Request $request,
        AINoteRepository $aINoteRepository,
        EntityManagerInterface $em, 
        AiNoteManager $aiNoteManager
    ): Response
    {
        /** @var User $user  */
        $user = $this->getUser();
        if(!$user) return $this->json([
            'code' => 403,
            'message' => 'Unauthorized',
        ], 403);

        $note = $aiNoteManager->init($user->getIdentity(), $aIcores);
        $note->setNote($request->get('star'));
        
        $em->persist($note);
        $em->flush();

        $desc = $aINoteRepository->findBy([
            'note' => $note->getNote()
        ]);

        return $this->json([
            'message' => 'Note ajouté',
            'note' => $note->getNote(),
            'desc' => $desc[0]->getDescription()
        ], 200);
    }

}
