<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class ResetPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, [
                'label' => 'Identifiant',
                'required' => true,
                'mapped' => false,
                'constraints' => new Length([
                    'min' => 3,
                    'minMessage' => 'Votre identifiant doit faire plus de {{ limit }} caractères',
                    'max' => 30,
                    'maxMessage' => 'Votre identifiant doit faire moins de {{ limit }} caractères'
                ]),
                'attr' => [
                    'placeholder' => 'Identifiant'
                ]
            ])

            ->add('question', ChoiceType::class, [
                'label' => 'Question secrète',
                'required' => true,
                'mapped' => false,
                'choices'  => [
                    'Dans quelle ville êtes-vous né(e) ?' => 1,
                    'Quel est le deuxième prénom de l’aîné(e) de votre fratrie ?' => 2,
                    'Quel est le premier concert auquel vous avez assisté ?' => 3,
                    'Quels étaient le fabricant et le modèle de votre première voiture ?' => 4,
                    'Dans quelle ville vos parents se sont-ils rencontrés ?' => 5
                ],
            ])

            ->add('answer', TextType::class, [
                'label' => 'Réponse secrète',
                'required' => true,
                'mapped' => false,
                'constraints' => new Length([
                    'min' => 3,
                    'minMessage' => 'Votre réponse doit faire plus de {{ limit }} caractères',
                    'max' => 30,
                    'maxMessage' => 'Votre réponse doit faire moins de {{ limit }} caractères'
                ]),
                'attr' => [
                    'placeholder' => 'Votre réponse'
                ],
            ])
            ->add('new_password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Le mot de passe et la confirmation doivent être identiques',
                'required' => true,
                'first_options' => [
                    'label' => 'Votre nouveau mot de passe', 
                    'attr' => [
                        'placeholder' => 'Votre nouveau mot de passe'
                    ]
                ],
                'second_options' => [
                    'label' => 'Confirmez votre nouveau mot de passe',
                    'attr' => [
                        'placeholder' => 'Confirmez votre nouveau mot de passe'
                    ]
                ],
                'constraints' => [
                    // new NotBlank([
                    //     'message' => 'Please enter a password',
                    // ]),
                    new Length([
                        'min' => 8,
                        'minMessage' => 'Votre nouveau mot de passe doit contenir au minimum {{ limit }} caractères',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096
                    ]),
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Modifier mon mot de passe',
                'attr' => [
                    'class' => 'reset-password-btn',
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
