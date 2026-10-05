<?php

namespace App\Form;

use App\Entity\Avis;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Range;

class AvisType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('note', ChoiceType::class, [
                'label' => 'Note sur 5 étoiles',
                'choices' => [
                    '5 étoiles' => 5,
                    '4 étoiles' => 4,
                    '3 étoiles' => 3,
                    '2 étoiles' => 2,
                    '1 étoile' => 1,
                ],
                'expanded' => true,
                'multiple' => false,
                'constraints' => [
                    new NotBlank(message: 'Choisis une note.'),
                    new Range(min: 1, max: 5, notInRangeMessage: 'La note doit être comprise entre {{ min }} et {{ max }}.'),
                ],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Message',
                'attr' => ['rows' => 5],
                'constraints' => [
                    new NotBlank(message: 'Écris ton avis avant de l’envoyer.'),
                    new Length(max: 255),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Avis::class,
        ]);
    }
}
