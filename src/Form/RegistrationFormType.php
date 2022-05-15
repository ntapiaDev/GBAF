<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
// use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('lastname', TextType::class, [
                'label' => 'Nom',
                'required' => true,
                'constraints' => new Length([
                    'min' => 3,
                    'minMessage' => 'Votre nom doit faire plus de {{ limit }} caractères',
                    'max' => 30,
                    'maxMessage' => 'Votre nom doit faire moins de {{ limit }} caractères'
                ]),
                'attr' => [
                    'placeholder' => 'Votre nom'
                ]
            ])

            ->add('firstname', TextType::class, [
                'label' => 'Prénom',
                'required' => true,
                'constraints' => new Length([
                    'min' => 3,
                    'minMessage' => 'Votre prénom doit faire plus de {{ limit }} caractères',
                    'max' => 30,
                    'maxMessage' => 'Votre prénom doit faire moins de {{ limit }} caractères'
                ]),
                'attr' => [
                    'placeholder' => 'Votre prénom'
                ]
            ])

            ->add('username', TextType::class, [
                'label' => 'Identifiant',
                'required' => true,
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

            // ->add('email', EmailType::class, [
            //     'label' => 'Adresse email',
            //     'required' => true,
            //     'constraints' => new Length([
            //         'min' => 6,
            //         'minMessage' => 'Votre adresse email doit faire plus de {{ limit }} caractères',
            //         'max' => 60
            //     ]),
            //     'attr' => [
            //         'placeholder' => 'Votre adresse email'
            //     ]
            // ])

            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Le mot de passe et la confirmation doivent être identiques',
                'required' => true,
                'first_options' => [
                    'label' => 'Votre mot de passe', 
                    'attr' => [
                        'placeholder' => 'Votre mot de passe'
                    ]
                ],
                'second_options' => [
                    'label' => 'Confirmez votre mot de passe',
                    'attr' => [
                        'placeholder' => 'Confirmez votre mot de passe'
                    ]
                ],
                'constraints' => [
                    // new NotBlank([
                    //     'message' => 'Please enter a password',
                    // ]),
                    new Length([
                        'min' => 8,
                        'minMessage' => 'Votre mot de passe doit contenir au minimum {{ limit }} caractères',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096
                    ]),
                ],
            ])

            ->add('question', ChoiceType::class, [
                'label' => 'Question secrète',
                'required' => true,
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

            ->add('agreeTerms', CheckboxType::class, [
                'label' => 'J\'accepte les conditions d\'utilisation de ce site',
                'required' => true,
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'J\'accepte les conditions d\'utilisation de ce site'
                    ]),
                ],
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
