<?php

namespace App\Form;

use App\Entity\Chauffeur;
use App\Entity\Course;
use App\Entity\Utilisateur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CourseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dateDepart', null, [
                'widget' => 'single_text',
            ])
            ->add('adresseDepart',null,[
                'label' => 'Adresse de départ',
            ])
            ->add('adresseArrivee',null,[
                'label'=> "Adresse d'arrivé"
            ])
            ->add('prix')
            ->add('nbPassagers')
            ->add('devis')
            ->add('chauffeur', EntityType::class, [
                'class' => Chauffeur::class,
                'choice_label' => function (Chauffeur $chauffeur) {
                    return $chauffeur->getNom() . ' ' . $chauffeur->getPrenom();
                },
            ])
            
            ->add('utilisateur', EntityType::class, [
                'class' => Utilisateur::class,
                'choice_label' => function(Utilisateur $utilisateur){
                    return $utilisateur->getNom(). ' '. $utilisateur->getPrenom();
                },
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Course::class,
        ]);
    }
}
