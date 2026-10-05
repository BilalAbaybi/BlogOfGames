<?php

namespace App\Form;

use App\Entity\Plateforme;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PlateformeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', options: ['label' => 'Nom de la plateforme', 'attr' => ['placeholder' => 'Ex. : PC, PlayStation 5…']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Plateforme::class,
        ]);
    }
}
