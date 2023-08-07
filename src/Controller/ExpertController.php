<?php

namespace App\Controller;

use App\Entity\Identity;
use App\Data\SearchPostData;
use App\Form\Search\SearchPostType;
use App\Repository\PostingRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ExpertController extends AbstractController
{
    #[Route('/dashboard/expert', name: 'app_expert')]
    public function index(
        Request $request,
        PostingRepository $postingRepository,
        PaginatorInterface $paginatorInterface
    ): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        /** @var Identity $identity */
        $identity = $user->getIdentity();

        if(!$identity instanceof Identity){
            return $this->redirectToRoute('app_account');
        }

        $postSearch = new SearchPostData();
        $form = $this->createForm(SearchPostType::class, $postSearch);
        $form->handleRequest($request);
        if($form->isSubmitted()){
            $data = $postingRepository->findSearch($postSearch);
        }else{
            $data = $postingRepository->findBySkills($identity->getAicores());
            foreach ($identity->getExperiences() as $value) {
                $data += $postingRepository->findBySkills($value->getSkills());
            }
        }

        $postings = $paginatorInterface->paginate(
            $data,
            $request->query->getInt('page', 1),
            6
        );


        return $this->render('expert/index.html.twig', [
            'identity' => $identity,
            'postings' => $postings,
            'form' => $form->createView()
        ]);
    }
}
