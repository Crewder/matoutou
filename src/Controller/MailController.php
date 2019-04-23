<?php
namespace App\Controller;
use App\Entity\User;
use App\Form\MailFormType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class MailController extends AbstractController
{
    /**
     * @Route("/admin/mail", name="app_mail")
     */
    public function MailAction(Request $request,\Swift_Mailer $mailer)
    {
        $em = $this->getDoctrine()->getManager();
        $id = $request->query->get('id');
        $user = $em->getRepository(User::Class)->find($id);
        $form = $this->createForm(MailFormType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){ 
            $sujet = $form->get('Sujet')->getData();
            $message = $form->get('Message')->getData();
            $message = (new \Swift_Message($sujet))
                ->setFrom('istorianima@gmail.com')
                ->setTo($user->getEmail())
                ->setBody($message);
                $mailer->send($message);
                return $this->redirectToRoute('easyadmin', array(
                    'action' => 'list',
                ));
        }
        return $this->render('form_mail.html.twig',['form' => $form->createView()]);  
    }

    /**
     * @Route("/admin/communaute", name="app_mail_communaute")
     */
    public function MailCommunauteAction(Request $request,\Swift_Mailer $mailer)
    {
        $em = $this->getDoctrine()->getRepository(User::class);
        $user = $em->findAll();
        $form = $this->createForm(MailFormType::class);
        $form->handleRequest($request);
        foreach($user as $key){
            if($form->isSubmitted() && $form->isValid()){ 
                $sujet = $form->get('Sujet')->getData();
                $message = $form->get('Message')->getData();
                $message = (new \Swift_Message($sujet))
                    ->setFrom('istorianima@gmail.com')
                    ->setTo($key->getEmail())
                    ->setBody($message);
                    $mailer->send($message);     
            }
            
        } 
        return $this->render('form_mail_communaute.html.twig',['form' => $form->createView()]);  
    }
}