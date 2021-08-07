<?php


namespace App\Controller;

use App\Entity\Token;
use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Form\ResetPasswordType;
use App\Form\SendEmailType;
use App\Manager\TokenManager;
use App\Manager\UserManager;
use App\Repository\UserRepository;
use App\Service\CheckTokenDate;
use App\Service\Mailer;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AccountController extends AbstractController
{
    /**
     * @Route("/register", name="register")
     */
    public function register(
        Request $request,
        UserPasswordHasherInterface $passwordEncoder,
        UserManager $userManager
    ): Response {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userManager->updatePassword($user, $form);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('trick_index');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('trick_index');
        }

        $error = $authenticationUtils->getLastAuthenticationError();

        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * @Route("/reset-password/{token}", name="reset_password", methods={"GET","POST"})
     * @Entity("token", options={"mapping": {"token": "token"}})
     * @throws \Exception
     */
    public function resetPassword(
        Request $request,
        EntityManagerInterface $entityManager,
        CheckTokenDate $checkTokenDate,
        UserManager $userManager,
        Token $token
    ) {
        $user =$token->getUser();

        if (!$checkTokenDate->isTokenDateValid($user, '10 minutes ago')) {
            throw $this->createNotFoundException();
        }

        $form = $this->createForm(ResetPasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userManager->updatePassword($user, $form);
            $user->setToken(null);

            $entityManager->flush();

            $this->addFlash('success', 'Votre mot de passe a été mis à jour avec succès, vous pouvez maintenant vous connecter avec vos nouveaux identifiants !');

            return $this->redirectToRoute('trick_index');
        }

        return $this->render('security/reset-password.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/forgot-password", name="forgot_password", methods={"GET","POST"})
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     */
    public function forgotPassword(
        Request $request,
        UserRepository $userRepository,
        TokenGeneratorInterface $tokenGenerator,
        Mailer $mailer,
        TokenManager $tokenManager
    ): Response {
        $form = $this->createForm(SendEmailType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $userRepository->findOneBy(['email' => $form->get('email')->getData()]);

            if ($user) {
                $tokenManager->create($user, $tokenGenerator);
                $mailer->sendMail($user);
            } else {
                $this->addFlash('error', 'Cette email n\'existe pas.');

                return $this->redirectToRoute('forgot_password');
            }
        }
        return $this->render('security/send-mail.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
