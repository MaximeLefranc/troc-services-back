<?php

namespace App\Form;

use App\Entity\Skill;
use App\Entity\User;
use Doctrine\DBAL\Types\SmallIntType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class User1TypeEdit extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email')
            ->add('roles', ChoiceType::class, [
                'choices'  => [

                    'Utilisateur' => 'ROLE_USER',
                    'Administrateur' => 'ROLE_ADMIN',
                ],
                // Choix multiple => Tableau ;)
                'multiple' => true,
                // checkboxes
                'expanded' => true,
            ])
            ->add('password', PasswordType::class, [
                // you can't add the passwordtype for the edition 
                // https://symfony.com/doc/current/reference/forms/types/form.html#mapped
                'mapped' => false,
                'attr' => [
                    'placeholder' => 'Laissez vide si inchangé'
                ]
            ])

            ->add('first_name')
            ->add('last_name')
            ->add('biography', TextareaType::class)
            ->add('address')
            ->add('city')
            ->add('zip_code')
            ->add('birth_date')
            ->add('nickname')
            ->add('imageFile', VichImageType::class)
            ->add(
                'Skill',
                EntityType::class,

                [
                    'class' => Skill::class,
                    'choice_label' => 'name',
                    'multiple' => true,
                    'expanded' => true
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
