<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use App\Entity\User;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;



class LoginFormController extends AbstractController
{
    /**
     * @Route("/matoutou/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }
    
    /**
     * @Route("/forgottenPassword", name="app_forgotten_password")
     */
    public function forgottenPassword(Request $request,UserPasswordEncoderInterface $encoder,\Swift_Mailer $mailer,TokenGeneratorInterface $tokenGenerator): Response
    {
        $user = $this->getUser();
        if ($request->isMethod('POST')) {
            $email = $request->request->get('email');
            $entityManager = $this->getDoctrine()->getManager();
            $user = $entityManager->getRepository(User::class)->findOneByEmail($email);
            if ($user === null) {
                $this->addFlash('danger', 'Email Inconnu');
            }
                $token = $tokenGenerator->generateToken();
            try{
                $user->setToken($token);
                $entityManager->flush();
            } catch (\Exception $e) {
                $this->addFlash('warning', $e->getMessage());
            }
            $url = $this->generateUrl('app_reset_password', array('token' => $token), UrlGeneratorInterface::ABSOLUTE_URL);
            $message = (new \Swift_Message('Mot de passe oublié'))
                ->setFrom('g.ponty@dev-web.io')
                ->setTo($user->getEmail())
                ->setBody(
                    "Suivez ce lien afin de modifier votre mot de passe : " . $url,
                    'text/html'
                );
            $mailer->send($message);
            $message = $this->addFlash('notice', 'Mail envoyé');
            return $this->redirectToRoute('app_login',['message'=>$message]);
        }
        return $this->render('security/forgotten_password.html.twig',['user' => $user]);
    }

    /**
     * @Route("/reset_password/{token}", name="app_reset_password")
     */
    public function resetPassword(Request $request, string $token, UserPasswordEncoderInterface $passwordEncoder)
    {

        if ($request->isMethod('POST')) {
            $entityManager = $this->getDoctrine()->getManager();

            $user = $entityManager->getRepository(User::class)->findOneByToken($token);
            /* @var $user User */

            if ($user === null) {
                $this->addFlash('danger', 'Token Inconnu');
                return $this->redirectToRoute('app_login');
            }

            $user->setToken(null);
            $user->setPassword($passwordEncoder->encodePassword($user, $request->request->get('password')));
            $entityManager->flush();

            $message = $this->addFlash('notice', 'Mot de passe mis à jour');
            return $this->redirectToRoute('app_login',['message'=>$message]);
            
        }else {
            return $this->render('security/reset_password.html.twig', ['token' => $token]);
        };

    }
    

}
