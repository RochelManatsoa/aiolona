<?php

namespace App\Controller;

use App\Data\SeachData;
use App\Entity\AIcores;
use App\Entity\Message;
use App\Entity\Posting;
use App\Entity\Identity;
use App\Entity\Experience;
use App\Entity\TechnicalSkill;
use App\Manager\AiNoteManager;
use App\Form\Search\SearchType;
use App\Manager\PostingManager;
use App\Manager\SkillNoteManager;
use App\Repository\AINoteRepository;
use App\Repository\AIcoresRepository;
use App\Repository\MessageRepository;
use App\Repository\IdentityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
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

    #[Route('/ajax-skill/{slug}/{star}', name: 'ajax_skills_stars')]
    public function skillsStars(
        TechnicalSkill $technicalSkill, 
        Request $request,
        AINoteRepository $aINoteRepository,
        EntityManagerInterface $em, 
        SkillNoteManager $skillNoteManager
    ): Response
    {
        /** @var User $user  */
        $user = $this->getUser();
        if(!$user) return $this->json([
            'code' => 403,
            'message' => 'Unauthorized',
        ], 403);

        $note = $skillNoteManager->init($user->getIdentity(), $technicalSkill);
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
        EntityManagerInterface $entityManager,
        SluggerInterface $sluggerInterface,
        Request $request
    ): Response
    {
        if (is_numeric($request->get('str'))) {
            $aicore = $entityManager->getRepository(AIcores::class)->find($request->get('str'));
        } else {
            // Trouvez ou créez une entité basée sur la valeur textuelle
            $aicore = $entityManager->getRepository(AIcores::class)->findOneBy([
                'name' => $request->get('str')
            ]);
            if(!$aicore instanceof AIcores){
                $aicore = new AIcores();
                $aicore
                    ->setName($request->get('str'))
                    ->setType('user')
                    ->setUrl('')
                    ->setSlug($sluggerInterface->slug($request->get('str')))
                ; 
                // ou toute autre opération pour initialiser l'entité
                $entityManager->persist($aicore);
                $entityManager->flush();
                
                // envoi email à Sahra pour la validation
                // $this->mailerService->send(
                //     "miandrisoa.olona@gmail.com",
                //     "Validation requise : Nouvel outil ajouté sur PostIn Expert",
                //     "new_ia_email.html.twig",
                //     [
                //         'user' => $this->userService->getCurrentUser(),
                //         'aicore' => $aicore,
                //         'url' => 'https://postin-expert.com/admin',
                //     ]
                // );
            }
        }
        return $this->json([
            'aicore' => $aicore,
        ], 200, [], ['groups' => 'identity']);
    }

    #[Route('/ajax-skill/{str}', name: 'ajax_get_skill')]
    public function getSkillById(
        EntityManagerInterface $entityManager,
        SluggerInterface $sluggerInterface,
        Request $request
    ): Response
    {
        if (is_numeric($request->get('str'))) {
            $aicore = $entityManager->getRepository(TechnicalSkill::class)->find($request->get('str'));
        } else {
            // Trouvez ou créez une entité basée sur la valeur textuelle
            $aicore = $entityManager->getRepository(TechnicalSkill::class)->findOneBy([
                'name' => $request->get('str')
            ]);
            if(!$aicore instanceof TechnicalSkill){
                $aicore = new TechnicalSkill();
                $aicore
                    ->setName($request->get('str'))
                    ->setType('user')
                    ->setUrl('')
                    ->setSlug($sluggerInterface->slug($request->get('str')))
                ; 
                // ou toute autre opération pour initialiser l'entité
                $entityManager->persist($aicore);
                $entityManager->flush();
                
                // envoi email à Sahra pour la validation
                // $this->mailerService->send(
                //     "miandrisoa.olona@gmail.com",
                //     "Validation requise : Nouvel outil ajouté sur PostIn Expert",
                //     "new_ia_email.html.twig",
                //     [
                //         'user' => $this->userService->getCurrentUser(),
                //         'aicore' => $aicore,
                //         'url' => 'https://postin-expert.com/admin',
                //     ]
                // );
            }
        }
        return $this->json([
            'skill' => $aicore,
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

    #[Route('/ajax-remove-exp/{experience}/{identity}', name: 'ajax_remove_exp')]
    public function removeExp(
        Experience $experience,
        Identity $identity,
        EntityManagerInterface $em
    ): Response
    {
        $identity->removeExperience($experience);
        $em->persist($identity);
        $em->remove($experience);
        $em->flush();    

        return $this->json([
            'content' => "Experience effacé"
        ], 200, []);
    }

    #[Route('/store/ajax', name: 'app_store_ajax')]
    public function store(
        AIcoresRepository $aIcoresRepository,
        Request $request,
    ): Response
    {
        $offset = $request->query->get('offset', 0);
        $aicores = $aIcoresRepository->findBy([
            'type' => 'publish'
        ], null, 12, $offset);


        return  $this->json([
            'html' => $this->renderView('components/product/_product.html.twig', [
                'aicores' => $aicores,
            ], []),
        ], 200, []);
    }

    #[Route('/all/expert/ajax', name: 'app_all_expert_ajax')]
    public function allExpert(
        IdentityRepository $identityRepository,
        Request $request,
    ): Response
    {
        $offset = $request->query->get('offset', 0);
        $dataType = new SeachData();
        $form = $this->createForm(SearchType::class, $dataType);
        $form->handleRequest($request);
        $identities = $identityRepository->findSearch($dataType, 13, $offset);

        return  $this->json([
            'html' => $this->renderView('components/identities/_identities.html.twig', [
                'identities' => $identities,
            ], []),
        ], 200, []);
    }

}
