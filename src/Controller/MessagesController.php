<?php

namespace App\Controller;

use App\Entity\Account;
use App\Entity\Message;
use App\Entity\Compagny;
use App\Form\MessagesType;
use App\Service\Cart\CartService;
use App\Service\User\UserService;
use App\Service\Mailer\MailerService;
use App\Service\Message\MessageService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MessagesController extends AbstractController
{
    public function __construct(
        private CartService $cartService,
        private UserService $userService,
        private MessageService $messageService
    )
    {
        $this->cartService = $cartService;
        $this->userService = $userService;
        $this->messageService = $messageService;
    }

    #[Route('/dashboard/messages', name: 'app_messages_dashboard')]
    public function messages(
        Request $request, 
        EntityManagerInterface $em, 
        MailerService $mailerService
        ): Response
    {
        $message = new Message();
        $form = $this->createForm(MessagesType::class, $message, []);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $message->setSender($this->getUser());
            $em->persist($message);
            $em->flush();

            // send an email
            $mailerService->send(
                $message->getRecipient()->getEmail(),
                "Vous avez reçu un nouveau message",
                "message_email.html.twig",
                [
                    'user' => $message->getRecipient(),
                    'sender' => $this->getUser(),
                    'url' => $this->generateUrl('app_messages_dashboard', [], UrlGeneratorInterface::ABSOLUTE_URL),
                ]
            );

            $this->addFlash('success', 'Message envoyé avec succès');

            return $this->redirectToRoute('app_messages_dashboard');
        }

        $params = $this->checkUserInfo();
        $params += ['form' => $form->createView()];
        
        if($params['identity']->getAccount()->getSlug() === Account::EXPERT) return $this->render('expert/messages.html.twig', $params);
        
        $params += [
            'items' => $this->cartService->getFullCart(),
            'total' => $this->cartService->getTotal(),
            'json' => json_encode($this->cartService->getCartSession()),
        ];

        return $this->render('company/messages.html.twig', $params);
    }

    private function checkUserInfo()
    {
        $identity = $this->userService->getCurrentIdentity();
        $company = $identity->getCompagny();
        if($company instanceof Compagny){
            return [
                'identity' => $identity,
                'company' => $company,
                'received' => $this->messageService->getReceivedMessages(),
                'sent' => $this->messageService->getSentMessages(),
                'count' => $this->cartService->getCount(),
                'items' => $this->cartService->getFullCart(),
                'total' => $this->cartService->getTotal(),
                'json' => json_encode($this->cartService->getCartSession()),
            ];
        }

        return [
            'identity' => $identity,
            'received' => $this->messageService->getReceivedMessages(),
            'sent' => $this->messageService->getSentMessages(),
        ];

    }
}
