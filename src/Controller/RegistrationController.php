<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Service\Mailer\MailerService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(
        Request $request, 
        UserPasswordHasherInterface $userPasswordHasher, 
        EntityManagerInterface $entityManager,
        MailerService $mailerService, 
        TokenGeneratorInterface $tokenGeneratorInterface
        ): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            // token
            $tokenRegistration = $tokenGeneratorInterface->generateToken();

            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $user->setTokenRegistration($tokenRegistration);

            $entityManager->persist($user);
            $entityManager->flush();

            // send an email
            $mailerService->send(
                $user->getEmail(),
                "Confirmation du compte utilisateur",
                "registration_email.html",
                [
                    'user' => $user,
                    'token' => $tokenRegistration,
                    'url' => $this->generateUrl('account_verify', ['token' => $tokenRegistration, 'id' => $user->getId()], UrlGeneratorInterface::ABSOLUTE_URL),
                    'lifeTimeToken' => $user->getTokenLifeTime()->format('d/M/Y à Hh:i')
                ]
            );

            $this->addFlash('info', 'Votre compte a bien été crée, veuillez vérifier vos e-mails pour l\'activer.');

            return $this->redirectToRoute('app_login');

        }

        return $this->render('registration/registration_form.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/verify/{token}/{id<\d+>}', name: 'account_verify')]
    public function verify(Request $request, string $token, User $user, EntityManagerInterface $em)
    {
        if($user->getTokenRegistration() !== $token){
                throw new AccessDeniedException('Le lien n\'est pas valide');
            }

        if($user->getTokenRegistration() === null){
            throw new AccessDeniedException('Le lien n\'est plus valide, votre compte est déjà activé.');
        }
        
        if(new \DateTime('now') > $user->getTokenLifeTime()){
            throw new AccessDeniedException('Token expiré');
        }

        $user->setIsVerified(true);
        $user->setTokenRegistration(null);
        $em->flush();

        $this->addFlash('success', 'Votre compte a bien été activé, vous pouvez maintenant vous connecter');

        return $this->redirectToRoute('app_login');

    }
    
}
