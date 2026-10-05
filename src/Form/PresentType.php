<?php

namespace App\Form;

use App\Entity\Jeux;
use App\Entity\Plateforme;
use App\Entity\Present;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PresentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('prix', options: ['label' => 'Prix'])
            ->add('jeux', EntityType::class, [
                'class' => Jeux::class,
                'choice_label' => 'titre',
                'label' => 'Jeu',
            ])
            ->add('plateforme', EntityType::class, [
                'class' => Plateforme::class,
                'choice_label' => 'nom',
                'label' => 'Plateforme',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Present::class,
        ]);
    }
}
