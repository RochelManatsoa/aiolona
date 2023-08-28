<?php

namespace App\Controller;

use App\Entity\AIcores;
use App\Entity\Posting;
use App\Entity\Message;
use App\Manager\AiNoteManager;
use App\Manager\PostingManager;
use App\Repository\AIcoresRepository;
use App\Repository\AINoteRepository;
use App\Repository\MessageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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

    #[Route('/ajax/posting/delete/{jobId}', name: 'ajax_posting_delete')]
    public function deletePosting(
        Posting $posting,
        PostingManager $postingManager,
        Request $request,
        EntityManagerInterface $em
    ): Response
    {
        
        $em->persist($posting);
        $em->flush();    

        $this->addFlash('warning', 'Posting deleted');
        return $this->json([
            'message' => 'Posting deleted',
        ], 200);
    }
    #[Route('/ajax/{str}', name: 'ajax_get_ia')]
    public function getIaById(
        AIcoresRepository $aIcoresRepository,
        Request $request
    ): Response
    {
        return $this->json([
            'aicore' => $aIcoresRepository->findOneBy([
                'id' => $request->get('str')
            ]),
        ], 200, [], ['groups' => 'identity']);
    }

    #[Route('/ajax/message/show/{message}', name: 'ajax_show_message')]
    public function showMessage(
        MessageRepository $messageRepository,
        Message $message,
        EntityManagerInterface $em
    ): Response
    {
        $message->setIsRead(1);
        $em->persist($message);
        $em->flush();    

        return $this->json([
            'content' => $messageRepository->findOneBy([
                'id' => $message->getId()
            ]),
        ], 200, [], ['groups' => 'message']);
    }

}
