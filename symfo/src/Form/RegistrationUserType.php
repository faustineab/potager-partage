<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class RegistrationUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'attr' =>[
                    'placeholder' => 'Entrez votre adresse email'
                ]
            ])
            ->add('name', TextType::class, [
                'label' => 'Nom et prenom',
                'attr' =>[
                    'placeholder' => 'Ajouter votre nom et prénom'
                ]
            ])
          
           -> add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'The password fields must match.',
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => true,
                'first_options'  => ['label' => 'Password'],
                'second_options' => ['label' => 'Repeat Password'],
                    ])
    
            ->add('phone', IntegerType::class, [
                'label' => 'Téléphone',
                'attr' =>[
                    'placeholder' => 'Ajouter votre numéro de téléphone'
                ]
            ])
            ->add('address', TextType::class, [
                'label' => 'Adresse',
                'attr' =>[
                    'placeholder' => 'Ajouter votre adresse'
                ]
            ])
            ->add('gardens', EntityType::class, [
                'class' => 'App\Entity\Garden',
                'choice_label' => 'name',
                'label' => 'Votre jardin',
                'expanded'     => true,
                'multiple'     => true,  
                      ])
        ;
 
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
