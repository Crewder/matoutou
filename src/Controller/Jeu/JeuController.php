<?php

namespace App\Controller\Jeu;

use App\Entity\Partie;
use App\Entity\Chat;
use App\Repository\CartesRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ChatRepository;


/**
 * Class JeuController
 * @package App\Jeu\Controller
 * @Route("/jeuCo")
 */
class JeuController extends AbstractController
{
    
    /**
     * @Route("/nouvelle-partie", name="jeu_index")
     */
    public function newGame(UserRepository $userRepository)
    {
        $adversaires = $userRepository->findAll();
        $randAdv = shuffle($adversaires);
        return $this->render('jeu/new.html.twig', [
            'user'        => $this->getUser(),
            'adversaires' => $adversaires
        ]);
    }

    /**
     * @Route("/creer-partie", name="create_game")
     */
    public function createGame(
        EntityManagerInterface $entityManager,
        Request $request,
        UserRepository $userRepository,
        CartesRepository $cartesRepository
    ) {
        $partie = new Partie();
        $partie->setJ1($this->getUser());
        $j2 = $userRepository->find($request->request->get('adversaire'));
        $partie->setJ2($j2);

        $cartes = $cartesRepository->findAll();

        $tJ1 = [];
        $tJ2 = [];
        $shogun1 = '';
        $shogun2 = '';

        /** @var Cartes $carte */
        foreach ($cartes as $carte) {
            if ($carte->getTeam() === 'J1') {
                if ($carte->isShogun()) {
                    $shogun1 = $carte->getId();
                } else {
                    $tJ1[] = $carte->getId();
                }
            }

            if ($carte->getTeam() === 'J2') {
                if ($carte->isShogun()) {
                    $shogun2 = $carte->getId();
                } else {
                    $tJ2[] = $carte->getId();
                }
            }
        }

        shuffle($tJ1);
        shuffle($tJ2);

        $terrainJ1 = [
            1 => [1 => $shogun1, 2 => $tJ1[0], 3 => $tJ1[1], 4 => $tJ1[2]],
            2 => [1 => $tJ1[3], 2 => $tJ1[4], 3 => $tJ1[5]],
            3 => [1 => $tJ1[6], 2 => $tJ1[7]],
            4 => [1 => $tJ1[8]],
            5 => [],
            6 => [],
            7 => [],
            8 => [],
            9 => [],
            10 => [],
            11 => []
        ];

        $partie->setTerrainJ1($terrainJ1);
        

        $terrainJ2 = [
            1 => [1 => $shogun2, 2 => $tJ2[0], 3 => $tJ2[1], 4 => $tJ2[2]],
            2 => [1 => $tJ2[3], 2 => $tJ2[4], 3 => $tJ2[5]],
            3 => [1 => $tJ2[6], 2 => $tJ2[7]],
            4 => [1 => $tJ2[8]],
            5 => [],
            6 => [],
            7 => [],
            8 => [],
            9 => [],
            10 => [],
            11 => []
        ];

        $partie->setTerrainJ2($terrainJ2);
        $partie->setTour(1);
        $partie->setMove(1);
        $partie->setDebutPartie(new \DateTime());
        $partie->setEtatPartie(1);

        $entityManager->persist($partie);
        $entityManager->flush();

        return $this->redirectToRoute('show_game', ['partie' => $partie->getId()]);
    }

    /**
     * @Route("/creer-newpartie", name="create_game_simple")
     */
    public function createSimpleGame(
        EntityManagerInterface $entityManager,
        Request $request,
        UserRepository $userRepository,
        CartesRepository $cartesRepository
    ) {
        $partie = new Partie();
        $partie->setJ1($this->getUser());
        $j2 = $userRepository->find($request->request->get('adversaire'));
        $partie->setJ2($j2);

        $cartes = $cartesRepository->findAll();

        $tJ1 = [];
        $tJ2 = [];
        $shogun1 = '';
        $shogun2 = '';

        /** @var Cartes $carte */
        foreach ($cartes as $carte) {
            if ($carte->getTeam() === 'J1') {
                if ($carte->isShogun()) {
                    $shogun1 = $carte->getId();
                } else {
                    $tJ1[] = $carte->getId();
                }
            }

            if ($carte->getTeam() === 'J2') {
                if ($carte->isShogun()) {
                    $shogun2 = $carte->getId();
                } else {
                    $tJ2[] = $carte->getId();
                }
            }
        }

        shuffle($tJ1);
        shuffle($tJ2);

        $terrainJ1 = [
            1 => [1 => $shogun1, 2 => $tJ1[0], 3 => $tJ1[1], 4 => $tJ1[2]],
            2 => [1 => $tJ1[3], 2 => $tJ1[4], 3 => $tJ1[5]],
            3 => [1 => $tJ1[6], 2 => $tJ1[7]],
            4 => [1 => $tJ1[8]],
            5 => [],
            6 => [],
            7 => [],
            8 => [],
            9 => [],
            10 => [],
            11 => []
        ];

        $partie->setTerrainJ1($terrainJ1);
        

        $terrainJ2 = [
            1 => [1 => $shogun2, 2 => $tJ2[0], 3 => $tJ2[1], 4 => $tJ2[2]],
            2 => [1 => $tJ2[3], 2 => $tJ2[4], 3 => $tJ2[5]],
            3 => [1 => $tJ2[6], 2 => $tJ2[7]],
            4 => [1 => $tJ2[8]],
            5 => [],
            6 => [],
            7 => [],
            8 => [],
            9 => [],
            10 => [],
            11 => []
        ];

        $partie->setTerrainJ2($terrainJ2);
        $partie->setTour(1);
        $partie->setMove(1);
        $partie->setDebutPartie(new \DateTime());
        $partie->setEtatPartie(1);

        $entityManager->persist($partie);
        $entityManager->flush();

        return $this->redirectToRoute('show_game', ['partie' => $partie->getId()]);
    }

    /**
     * @Route("/creer-friend-partie/{pseudo}", name="create_friend_game")
     */
    public function createFriendGame(
        EntityManagerInterface $entityManager,
        Request $request,
        UserRepository $userRepository,
        CartesRepository $cartesRepository,
        string $pseudo,
        \Swift_Mailer $mailer
    ) {
        $partie = new Partie();
        $partie->setJ1($this->getUser());
        $j2 = $userRepository->findOneByPseudo($pseudo);
        $partie->setJ2($j2);

        $cartes = $cartesRepository->findAll();

        $tJ1 = [];
        $tJ2 = [];
        $shogun1 = '';
        $shogun2 = '';

        /** @var Cartes $carte */
        foreach ($cartes as $carte) {
            if ($carte->getTeam() === 'J1') {
                if ($carte->isShogun()) {
                    $shogun1 = $carte->getId();
                } else {
                    $tJ1[] = $carte->getId();
                }
            }

            if ($carte->getTeam() === 'J2') {
                if ($carte->isShogun()) {
                    $shogun2 = $carte->getId();
                } else {
                    $tJ2[] = $carte->getId();
                }
            }
        }

        shuffle($tJ1);
        shuffle($tJ2);

        $terrainJ1 = [
            1 => [1 => $shogun1, 2 => $tJ1[0], 3 => $tJ1[1], 4 => $tJ1[2]],
            2 => [1 => $tJ1[3], 2 => $tJ1[4], 3 => $tJ1[5]],
            3 => [1 => $tJ1[6], 2 => $tJ1[7]],
            4 => [1 => $tJ1[8]],
            5 => [],
            6 => [],
            7 => [],
            8 => [],
            9 => [],
            10 => [],
            11 => []
        ];

        $partie->setTerrainJ1($terrainJ1);
        

        $terrainJ2 = [
            1 => [1 => $shogun2, 2 => $tJ2[0], 3 => $tJ2[1], 4 => $tJ2[2]],
            2 => [1 => $tJ2[3], 2 => $tJ2[4], 3 => $tJ2[5]],
            3 => [1 => $tJ2[6], 2 => $tJ2[7]],
            4 => [1 => $tJ2[8]],
            5 => [],
            6 => [],
            7 => [],
            8 => [],
            9 => [],
            10 => [],
            11 => []
        ];

        $partie->setTerrainJ2($terrainJ2);
        $partie->setTour(1);
        $partie->setMove(1);
        $partie->setDebutPartie(new \DateTime());
        $partie->setEtatPartie(1);

        $entityManager->persist($partie);
        $entityManager->flush();
        $message = (new \Swift_Message('Invitation à jouer'))
                ->setFrom('bla@gmail.com')
                ->setTo($j2->getEmail())
                ->setBody(
                    "On vous a envoyé une invitation à jouer! Rejoignez vite !",
                    'text/html'
                );
        $mailer->send($message);

        return $this->redirectToRoute('app_matoutou_joueur');
    }

    /**
     * @Route("/affiche-partie/{partie}", name="show_game")
     */
    public function showGame(Partie $partie, CartesRepository $cartesRepository)
    {
        $cartes = $cartesRepository->findAll();
        $tCartes = [];
        foreach ($cartes as $carte) {
            $tCartes[$carte->getId()] = $carte;
        }

        if ($partie->getJ1()->getId() === $this->getUser()->getId()) {
            //en bas c'est J1, adversaire = J2;
            $terrainJoueur = $partie->getTerrainJ1();
            $terrainAdversaire = $partie->getTerrainJ2();
        } else {
            $terrainAdversaire = $partie->getTerrainJ1();
            $terrainJoueur = $partie->getTerrainJ2();
        }

        return $this->render('jeu/affiche.html.twig', [
            'partie'            => $partie,
            'terrainAdversaire' => $terrainAdversaire,
            'terrainJoueur'     => $terrainJoueur,
            'tCartes'           => $tCartes
        ]);
    }

    /**
     * @Route("/delete_partie/{partie}", name="delete_partie")
     */
    public function deleteGame(Partie $partie,EntityManagerInterface $em ,CartesRepository $cartesRepository,EntityManagerInterface $entityManager)
    {
            $em->remove($partie);
            $em->flush();
            return $this->redirectToRoute('app_matoutou_joueur');   
    }

    /**
     * @param Request $request
     * @Route("/deplacement-partie/{partie}", name="deplacement_game", methods={"POST"})
     */
    public function move(EntityManagerInterface $entityManager, CartesRepository $carteRepository, Request $request, Partie $partie) {
        $carte = $carteRepository->find($request->request->get('id'));

        if ($carte !== null)
        {
            $numPile = $request->request->get('pile');
            $position = $request->request->get('position');
            $valeurDeplacement = $request->request->get('valeur');

            $terrainJoueur = null;
            $terrainAdv = null;

            //vérifie si le joueur connecté est le joueur 1
            if ($this->getUser()->getId() === $partie->getJ1()->getId())
            {
                $terrainJoueur = $partie->getTerrainJ1();
                $terrainAdv = $partie->getTerrainJ2();
            } else {
                $terrainJoueur = $partie->getTerrainJ2();
                $terrainAdv = $partie->getTerrainJ1();
            }

            $pileDepart = $terrainJoueur[$numPile];
            $terrainJoueur[$numPile] = [];

            $pileDestination = $numPile + $valeurDeplacement;
            //fait en sorte que la pile de destination soit entre 1 et 11
            if ($pileDestination > 11)
            {
                $pileDestination = 11;
            }

            $pileDestinationAdv = 11 - $numPile - $valeurDeplacement +1;
            //fait en sorte que la pile de destination du côté adverse soit entre 1 et 11
            if ($pileDestinationAdv < 0)
            {
                $pileDestinationAdv = 0;
            }
            $nbCartes = count($terrainJoueur[$pileDestination]);
            $i = 1;
            if ($nbCartes === 0) {
                $terrainJoueur[$pileDestination] = [];
            }
            //rajoute les cartes dans la pile de destination
            foreach ($pileDepart as $index => $idCarte)
            {
                if ($i >= $position)
                {
                    $terrainJoueur[$pileDestination][$nbCartes + $index] = $idCarte;
                } else {
                    $terrainJoueur[$numPile][$index] = $idCarte;
                }
                $i++;
            }

            if ($this->getUser()->getId() === $partie->getJ1()->getId())
            {
                $partie->setTerrainJ1($terrainJoueur);
            } else {
                $partie->setTerrainJ2($terrainJoueur);
            }

            $entityManager->flush();


            if ($pileDestinationAdv > 0)
            {
                if (count($terrainAdv[$pileDestinationAdv]) > 0)
                {
                    $idCombattantJoueur = end($terrainJoueur[$pileDestination]);
                    $idCombattantAdv = end($terrainAdv[$pileDestinationAdv]);
                    return $this->json(['etat' => 'conflit', 'idCombattantJoueur' => $idCombattantJoueur, 'idCombattantAdv' => $idCombattantAdv, 'pileDestination' => $pileDestination, 'pileDestinationAdv' => $pileDestinationAdv,'taillePile1' => count($terrainJoueur[$pileDestination]), 'taillePile2' => count($terrainAdv[$pileDestinationAdv])]);
                } else {
                    return $this->json(['etat' => 'pas de conflit']);
                }
            }
        }
        return $this->json('OK', Response::HTTP_OK);
    }

    /**
     * @param Request $request
     * @Route("/resolve-conflict/{partie}", name="resolve_conflict_game", methods={"POST"})
     */
    public function resolveConflict(CartesRepository $cartesRepository, Request $request, Partie $partie,EntityManagerInterface $entityManager
    )
    {
        
        $carteJ1 = $cartesRepository->find($request->request->get('idCombattantJoueur'));
        $carteJ2 = $cartesRepository->find($request->request->get('idCombattantAdv'));
        $pile1 = $request->request->get('pileDestination');
        $pile2 = $request->request->get('pileDestinationAdv');
        
        if ($carteJ1 && $carteJ2) {
            if($carteJ1->getCouleur() == $carteJ2->getCouleur()){
                if ($carteJ1->getForceCarte() > $carteJ2->getForceCarte()) {
                    if ($this->getUser()->getId() === $partie->getJ1()->getId())
                    {
                        $terrain2 = $partie->getTerrainJ2(); 
                    } else {
                        $terrain2 = $partie->getTerrainJ1();
                    }

                    $tab = array_pop($terrain2[$pile2]);

                    if ($this->getUser()->getId() === $partie->getJ1()->getId())
                    {
                        $partie->setTerrainJ2($terrain2);
                    } else {
                        $partie->setTerrainJ1($terrain2);
                    }
                    $entityManager->flush();
                    // return $this->json(['test' => 'baya', 'vainqueur' => $carteJ1->getId(),'perdant' =>$tab, 'terrain' => $terrain2]);

                }elseif($carteJ1->getForceCarte() < $carteJ2->getForceCarte()){
                    if ($this->getUser()->getId() === $partie->getJ1()->getId())
                    {
                        $terrain1 = $partie->getTerrainJ1();
                    } else {
                        $terrain1 = $partie->getTerrainJ2();
                    }
                    $tab = array_pop($terrain1[$pile1]);

                    if ($this->getUser()->getId() === $partie->getJ1()->getId())
                    {
                        $partie->setTerrainJ1($terrain1);
                    } else {
                        $partie->setTerrainJ2($terrain1);
                    }
                    $entityManager->flush();
                    // return $this->json(['test' => 'baya2', 'vainqueur' => $carteJ2->getId(),'perdant' =>$tab, 'terrain' => $terrain1]);
                }elseif($carteJ1->getForceCarte() === $carteJ2->getForceCarte()){
                    if ($this->getUser()->getId() === $partie->getJ1()->getId())
                    {
                        $terrain1 = $partie->getTerrainJ1();
                        $terrain2 = $partie->getTerrainJ2();
                    } else {
                        $terrain1 = $partie->getTerrainJ2();
                        $terrain2 = $partie->getTerrainJ1();
                    }
                    $terrain1[$pile1 - 1][] = $carteJ1->getId();
                    array_pop($terrain1[$pile1]);
                    $terrain2[$pile2 - 1][] = $carteJ2->getId();
                    array_pop($terrain2[$pile2]);

                    $partie->setTerrainJ1($terrain1);
                    $partie->setTerrainJ2($terrain2);

                    $entityManager->flush();
                }
            }elseif($carteJ1->getCouleur() == "bleu" && $carteJ2->getCouleur() == "rouge" || 
            $carteJ1->getCouleur() == "rouge" && $carteJ2->getCouleur() == "blanc" || 
            $carteJ1->getCouleur() == "blanc" && $carteJ2->getCouleur() == "bleu")
            {
                if ($this->getUser()->getId() === $partie->getJ1()->getId())
                {
                    $terrain2 = $partie->getTerrainJ2(); 
                } else {
                    $terrain2 = $partie->getTerrainJ1();
                }

                $tab = array_pop($terrain2[$pile2]);

                if ($this->getUser()->getId() === $partie->getJ1()->getId())
                {
                    $partie->setTerrainJ2($terrain2);
                } else {
                    $partie->setTerrainJ1($terrain2);
                }

                
                $entityManager->flush();
            }
            elseif($carteJ2->getCouleur() == "bleu" && $carteJ1->getCouleur() == "rouge" || 
            $carteJ2->getCouleur() == "rouge" && $carteJ1->getCouleur() == "blanc" || 
            $carteJ2->getCouleur() == "blanc" && $carteJ1->getCouleur() == "bleu"){
            if ($this->getUser()->getId() === $partie->getJ1()->getId())
            {
                $terrain1 = $partie->getTerrainJ1();
            } else {
                $terrain1 = $partie->getTerrainJ2();
            }
                $tab = array_pop($terrain1[$pile1]);

                if ($this->getUser()->getId() === $partie->getJ1()->getId())
                {
                    $partie->setTerrainJ1($terrain1);
                } else {
                    $partie->setTerrainJ2($terrain1);
                }

                $entityManager->flush();
            }
            
            
            $idCombattantJoueur = end($terrain1[$pile1]);
            $idCombattantAdv = end($terrain2[$pile2]);
            
            
            
            
            return $this->json(['taillePile1' => count($terrain1[$pile1]), 'taillePile2' => count($terrain2[$pile2]),'idCombattantJoueur2' => $idCombattantJoueur, 'idCombattantAdv2' => $idCombattantAdv]);    
        }
    
        
        
    }

    /**
     * @param Request $request
     * @Route("/refresh-terrain/{partie}", name="refresh_game")
     */
    public function refreshTerrain(CartesRepository $cartesRepository, Partie $partie)
    {
        $cartes = $cartesRepository->findAll();
        $tCartes = [];
        foreach ($cartes as $carte) {
            $tCartes[$carte->getId()] = $carte;
        }
        if ($partie->getJ1()->getId() === $this->getUser()->getId()) {
            $terrainJoueur = $partie->getTerrainJ1();
            $terrainAdversaire = $partie->getTerrainJ2();
        } else {
            $terrainAdversaire = $partie->getTerrainJ1();
            $terrainJoueur = $partie->getTerrainJ2();
        }

        return $this->render('jeu/terrain.html.twig', [
            'partie'            => $partie,
            'terrainAdversaire' => $terrainAdversaire,
            'terrainJoueur'     => $terrainJoueur,
            'tCartes'           => $tCartes
        ]);
    
    }



    /**
     * @param Request $request
     * @Route("/chat/217", name="chat_envoie", methods={"GET"})
     */
    public function chat(Request $request,EntityManagerInterface $em){
        $chat =  new Chat();
        $message = $request->query->get('messageChat');
        $chat->setMessage($message);
        $chat->setPartie(217);
        $chat->setJ1(1);
        $chat->setJ2(2);
        $em->persist($chat);
        $em->flush();
        return $this->json('OK', Response::HTTP_OK);        
    }

    /**
     * @param Request $request
     * @Route("/chat/affiche/217", name="chat_affiche")
     */
    public function chatAffiche(Request $request,EntityManagerInterface $em, ChatRepository $chat){
        $recupChat =  $chat->findAll();
        foreach($recupChat as $value){
            echo $value['message'];
        }
               
    }

   
    /**
     * @param Request $request
     * @Route("/which-turn/{partie}", name="which_turn")
     */
    public function whichTurn(Partie $partie)
    {
        if ($this->getUser()->getId() === $partie->getJ1()->getId() && $partie->getTour() === 1)
        {
            return $this->json(['montour' => true, 'tour' => $partie->getTour()]);
        } elseif ($this->getUser()->getId() === $partie->getJ1()->getId() && $partie->getTour() === 2) {
            return $this->json(['montour' => false, 'tour' => $partie->getTour()]);
        }

        if ($this->getUser()->getId() === $partie->getJ2()->getId() && $partie->getTour() === 2)
        {
        return $this->json(['montour' => true, 'tour' => $partie->getTour()]);
        } elseif ($this->getUser()->getId() === $partie->getJ2()->getId() && $partie->getTour() === 1) {
            return $this->json(['montour' => false, 'tour' => $partie->getTour()]);
        }
        return $this->json('ok');
    }

    /**
     * @param Request $request
     * @Route("/change-turn/{partie}", name="change_turn")
     */
    public function changeTurn(Partie $partie, EntityManagerInterface $entityManager)
    {
        if ($partie->getTour() === 1)
        {
            $partie->setTour(2);
            $entityManager->persist($partie);
            $entityManager->flush();
        } else {
            $partie->setTour(1);
            $entityManager->persist($partie);
            $entityManager->flush();
        }
        return $this->json('OK', Response::HTTP_OK);       
    }

    /**
     * @param Request $request
     * @Route("/which-move/{partie}", name="which_move")
     */
    public function whichMove(Partie $partie)
    {
        return $this->json($partie->getMove());
    }

    /**
     * @param Request $request
     * @Route("/change-move/{partie}", name="change_move",methods={"POST"})
     */
    public function changeMove(Partie $partie, EntityManagerInterface $entityManager)
    {
        if ($partie->getMove() === 1)
        {
            $partie->setMove(2);
            $entityManager->flush();
        } else {
            $partie->setMove(1);
            $entityManager->flush();
        }
        return $this->json('OK', Response::HTTP_OK);       
    }
     


}
