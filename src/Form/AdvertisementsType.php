<?php

namespace App\Form;

use App\Entity\Advertisements;
use App\Entity\Categories;
use App\Entity\User;
use App\Entity\Skill;
use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdvertisementsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class,
            [
                "label" => "Titre de l'annonce:" ,
                "attr" => [
                    "placeholder" => "Saisissez le titre de l'annonce"
            ]
            
            ])
            ->add('content',  TextareaType::class)
            //ajouter l'image
            // is_hidden manque
            //gérer la visibilité de l'annonce
            ->add('category', EntityType::class,
            
            [
                'class' => Categories::class,
                'choice_label' => 'name',
                'multiple' => false,
                'expanded' => true
            ])
            ->add('skills', EntityType::class,
            
            [
                'class' => Skill::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true
            ])
            ->add('user', EntityType::class,
            [
                'class' => User::class,
                'choice_label' => 'email',
                'attr' => array('readonly' => true)
            ])
                
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Advertisements::class,
        ]);
    }
}
