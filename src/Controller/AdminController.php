<?php

// src/Controller/AdminController.php    
namespace App\Controller;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AdminController as BaseAdminController;

class AdminController extends BaseAdminController
{
    public function avertirAction()
    {
        $id = $this->request->query->get('id');
        $entity = $this->em->getRepository(User::class)->find($id);
        $entity->setAvertissement($entity->getAvertissement()+1);
        
        if($entity->getAvertissement() == 3){
            $entity->setEtatJoueur(true);
        }else if($entity->getAvertissement() == 0){
            $entity->setEtatJoueur(false);
        }else if($entity->getAvertissement() == 4){
            $entity->setEtatJoueur(false);
            $entity->setAvertissement(0);
        };
        $this->em->flush();

        return $this->redirectToRoute('easyadmin', array(
            'action' => 'list',
            'entity' => $this->request->query->get('entity'),
        ));
    }

   
}