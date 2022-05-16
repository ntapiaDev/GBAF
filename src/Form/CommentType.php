<?php

namespace App\Form;

use App\Entity\Comment;
use App\Entity\Partner;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('comment', TextareaType::class, [
                'label' => 'Votre commentaire :'
            ])

            ->add('partner', HiddenType::class)
            ->add('user', HiddenType::class)

            ->add('send', SubmitType::class, [
                'label' => 'Envoyer'
            ])
        ;

        $builder->get('partner')
            ->addModelTransformer(new CallbackTransformer(
                fn (Partner $partner) => $partner->getId(),
                fn (Partner $partner) => $partner->getPartner()
            ))
        ;
        $builder->get('user')
            ->addModelTransformer(new CallbackTransformer(
                fn (User $user) => $user->getId(),
                fn (User $user) => $user->getUsername()
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
            'csrf_token_id' => 'comment-add'
        ]);
    }
}