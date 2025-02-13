<?php

namespace App\Form;

use App\Entity\Annonce;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class AnnonceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('titre', TextType::class, [
            'label' => 'Titre de l\'annonce'
        ])
        ->add('description', TextareaType::class, [
            'label' => 'Description de l\'annonce'
        ])
        ->add('profilRecherche', TextType::class,[
            'label' => 'Profil recherché'
        ])
        ->add('salaire' , NumberType::class, [
            'required' => false,
                'label' => 'Salaire',
                'scale' => 2,
                'attr' => ['step' => '0.01']
        ])
        ->add('createdAt', DateTimeType::class,[
            'data' => new \DateTime(),
            'label'=> 'Date de création'
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Annonce::class
        ]);
    }
}