<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class UserType extends AbstractType
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
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
