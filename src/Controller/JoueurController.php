<?php

// src/Controller/MatoutouController.php    
namespace App\Controller;
use App\Entity\User;
use App\Entity\Partie;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Form\UpdateFormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Captcha\Bundle\CaptchaBundle\Form\Type\CaptchaType;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use App\Repository\PartieRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UserRepository;

class  JoueurController extends AbstractController{
    
    /**
     *  @Route("/joueur", name="app_matoutou_joueur")
     */
    public function index(PartieRepository $partieRepository){
        $user = $this->getUser();
        $user->setDerniereCo(new \DateTime());
        $user->setStatutJoueur(1);
        $em = $this->getDoctrine()->getManager(); // on récupère la gestion des entités
        $em->persist($user); // on effectue les mise à jours internes
        $em->flush(); // on effectue la mise à jour vers la base de données
        $partie = $em->getRepository(Partie::class)->findAll();
        $joueur = $em->getRepository(User::class)->findAll();
        return $this->render('joueur.html.twig',array('user'=>$user,'partie' =>$partie,'joueur'=>$joueur ));
    }

    /**
     *  @Route("/amis", name="app_matoutou_amis")
     */
    public function listeAmis(){
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $joueur = $em->getRepository(User::class)->findAll();
        return $this->render('amis.html.twig',array('user'=>$user,'joueur' => $joueur));
    }

    /**
     *  @Route("/joueur/amis", name="app_matoutou_friend")
     */
    public function addFriend(Request $request,\Swift_Mailer $mailer,TokenGeneratorInterface $tokenGenerator){
        $perso = $this->getUser();
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
            $url = $this->generateUrl('app_accept_friend', array('token' => $token),UrlGeneratorInterface::ABSOLUTE_URL);
            $message = (new \Swift_Message('Demande d\'amis'))
                ->setFrom('bla@gmail.com')
                ->setTo($user->getEmail())
                ->setBody(
                    $perso->getPseudo()." vous a demandé en ami ! Cliquez sur ce lien pour accepter ou sinon ignorez ce message." . $url.'?pseudoJoueur='.$perso->getPseudo(),
                    'text/html'
                );
            $mailer->send($message);
            $this->addFlash('notice', 'Demande envoyée');
            return $this->redirectToRoute('app_matoutou_joueur');
        }
        return $this->render('demande_amis.html.twig');
    }

    /**
     * @Route("/amis/valider{token}", name="app_accept_friend")
     */
    public function validerFriend(Request $request, string $token)
    {
        $pseudo = $_GET['pseudoJoueur'];
        if ($request->isMethod('POST')) {
            $entityManager = $this->getDoctrine()->getManager();
            $user = $entityManager->getRepository(User::class)->findOneByToken($token);
            $user2 = $entityManager->getRepository(User::class)->findOneByPseudo($pseudo);
            if ($user === null) {
                $this->addFlash('danger', 'Token Inconnu');
                return $this->redirectToRoute('app_login');
            }

            $user->setToken(null);
            if($request->request->get('valider')){
                $tab = $user->getFriendList();
                $tab2 = $user2->getFriendList();
                array_push($tab,$pseudo);
                array_push($tab2,$user->getPseudo());
                $user->setFriendList($tab);
                $user2->setFriendList($tab2);
                
            }
            
            $entityManager->flush();

            $messageconf = $this->addFlash('notice', 'Demande acceptée');
            return $this->redirectToRoute('app_login',['messageconf'=>$messageconf]);
            
        }else {
            return $this->render('valider_friend.html.twig', ['token' => $token,'id' => $pseudo]);
        };

    }

    /**
     * @Route("/amis/delete/{id}/{pseudo}", name="delete_friend")
     */
    public function deleteFriend(Request $request,int $id,string $pseudo,EntityManagerInterface $em, UserRepository $user)
    {
        if($request->request->get('valider')){
            $j1 = $this->getUser();
            $j1Pseudo = $j1->getPseudo();
            $amis = $j1->getFriendList();
            $j2 = $user->findOneByPseudo($pseudo);
            $amis2 = $j2->getFriendList();
            unset($amis[$id]);
            unset($amis2[array_search($j1Pseudo, $amis2)]);
            $j1->setFriendList($amis);
            $j2->setFriendList($amis2);
            $em->flush();
            return $this->redirectToRoute('app_matoutou_amis');  
        }   
        return $this->render('delete_friend.html.twig');
    }

    /**
     *  @Route("/joueur/modifier", name="app_matoutou_update")
     */
    public function modification(Request $request, UserPasswordEncoderInterface $passwordEncoder){
        $user = $this->getUser();
        $user->setDerniereCo(new \DateTime());
        $form = $this->createFormBuilder($user)
        ->add('pseudo',TextType::class,['label' => false,'attr' =>['class' =>'inputinscription1','placeholder'=> 'Nouveau pseudo']])
        ->add('password', RepeatedType::class, [
            'type' => PasswordType::class,
            'invalid_message' => 'Les mots de passe ne correspondent pas',
            'first_options'  => ['label' => false, 'attr' => ['placeholder'=> 'Nouveau mot de passe', 'class' =>'inputinscription1']],
            'second_options' => ['label' => false,'attr' => ['placeholder'=> 'Répéter le mot de passe', 'class' =>'inputinscription1']],  
        ])
        ->add('Sauvegarder',SubmitType::class,['attr'=>['class' =>'submitinscription']])
        ->getForm();
        $form->handleRequest($request); // hydratation du form 
        if($form->isSubmitted() && $form->isValid()){ // test si le formulaire a été soumis et s'il est valide
            $password = $passwordEncoder->encodePassword($user, $user->getPassword());
            $user->setPassword($password);
            $em = $this->getDoctrine()->getManager(); // on récupère la gestion des entités
            $em->flush(); // on effectue la mise à jour vers la base de données
            return $this->redirectToRoute('app_matoutou_joueur');  
        }

        return $this->render('modif_profil.html.twig',['form' => $form->createView(),'user' => $user]);  
    }

    /**
     * @Route("/matoutou/deconnexion", name="app_logout")
     */
    public function logout()
    {
        $user = $this->getUser();
        $user->setStatutJoueur(0);
        $em = $this->getDoctrine()->getManager(); // on récupère la gestion des entités
        $em->persist($user); // on effectue les mise à jours internes
        $em->flush(); // on effectue la mise à jour vers la base de données
        session_destroy();
        return $this->redirectToRoute('app_matoutou_index');  

    }
  
}

?>