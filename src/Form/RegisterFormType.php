<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Captcha\Bundle\CaptchaBundle\Form\Type\CaptchaType;
use EWZ\Bundle\RecaptchaBundle\Form\Type\EWZRecaptchaType;
use EWZ\Bundle\RecaptchaBundle\Validator\Constraints\IsTrue as RecaptchaTrue;

class RegisterFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('pseudo',TextType::class,['label' => false,'attr'=> [
                'placeholder' => 'Pseudo',
                'class' => 'inputinscription1']
            ])
            ->add('email',EmailType::class,[
                'required' => true,
                'label' => false,
                'attr'=> [
                    'placeholder' => 'Email',
                    'class' => 'inputinscription2']
                
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Les mots de passe ne correspondent pas',
                'required' => true,
                'first_options'  => ['label' => false, 'attr' => ['placeholder'=> 'Mot de passe', 'class' => 'inputinscription1']],
                'second_options' => ['label' => false,'attr' => ['placeholder'=> 'Répéter le mot de passe', 'class' => 'inputinscription2']],  
            ])
            ->add('recaptcha', EWZRecaptchaType::class,['label' => false,'required' => true])
            ->add('Confirmer', SubmitType::class,[
                'attr' =>[ 'class' => 'submitinscription bleu']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
