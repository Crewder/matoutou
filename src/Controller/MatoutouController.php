<?php

// src/Controller/MatoutouController.php    
namespace App\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Form\RegisterFormType;
use App\Entity\User;

class MatoutouController extends AbstractController{
    
    /**
     *  @Route("/matoutou", name="app_matoutou_depart")
     */
    public function index(){
        $user = $this->getUser();
        return $this->render('depart.html.twig',['user' => $user]);
    }

    /**
     *  @Route("/matoutou/home", name="app_matoutou_index")
     */
    public function accueil(){
        $user = $this->getUser();
        return $this->render('test.html.twig',['user' => $user]);
    }

    /**
     *  @Route("/matoutou/regles", name="app_matoutou_regles")
     */
    public function regles(){
        $user = $this->getUser();
        return $this->render('regles.html.twig',['user' => $user]);
    }
    
    /**
     *  @Route("/matoutou/inscription", name="app_matoutou_inscription")
     */
    public function inscription(Request $request, UserPasswordEncoderInterface $passwordEncoder,\Swift_Mailer $mailer){
        $task = new User;
        $task->setDateInscription(new \DateTime());
        $task->setDerniereCo(new \DateTime());
        $task->setStatutJoueur(false);
        $task->setRoles(['ROLE_USER']);

        $task->setFriendList([]);
        $task->setEtatJoueur(false);
        $task->setAvertissement(0);
        $form = $this->createForm(RegisterFormType::class, $task, [ 'action' => $this->generateUrl('app_matoutou_inscription'),]);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){ 
            $password = $passwordEncoder->encodePassword($task, $task->getPassword());
            $task->setPassword($password);
            $em = $this->getDoctrine()->getManager();
            $em->persist($task); 
            $em->flush(); 
            // ENVOIE DE MAIL
            $message = (new \Swift_Message('Confirmation inscription'))
                ->setFrom('istorianima@gmail.com')
                ->setTo($task->getEmail())
                ->setBody('Félicitation, vous êtes bien inscrits à notre jeu !');
            $mailer->send($message);
            return $this->redirectToRoute('app_login');
        }
        return $this->render('inscription.html.twig',['form' => $form->createView()]);  
    }
    

   
    
    
}

?>