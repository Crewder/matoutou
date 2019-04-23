<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class MailFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Sujet',TextType::class,['label' => false,'required' => true,'attr'=> [
                'placeholder' => 'Sujet du message']
            ])
            ->add('Message',TextareaType::class,[
                'required' => true,
                'label' => false,             
            ])

            ->add('Envoyer le mail', SubmitType::class)
        ;
    }

}
