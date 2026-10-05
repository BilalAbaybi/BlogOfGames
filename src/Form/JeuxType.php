<?php

namespace App\Form;

use App\Entity\Genre;
use App\Entity\Jeux;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Url;

class JeuxType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', options: ['label' => 'Titre du jeu', 'attr' => ['placeholder' => 'Ex. : The Legend of Zelda']])
            ->add('date_sortie', options: ['label' => 'Date de sortie', 'widget' => 'single_text', 'required' => false])
            ->add('description', options: ['label' => 'Résumé', 'attr' => ['rows' => 4]])
            ->add('detail', options: ['label' => 'Détails complémentaires', 'attr' => ['rows' => 4]])
            ->add('img', options: ['label' => 'Image de couverture (chemin)', 'required' => false])
            ->add('galerieFichiers', FileType::class, [
                'label' => 'Captures d’écran',
                'mapped' => false,
                'required' => false,
                'multiple' => true,
                'help' => 'Tu peux sélectionner plusieurs images (JPG, PNG ou WebP, 8 Mo maximum par image).',
                'constraints' => [
                    new All([
                        new File(
                            maxSize: '8M',
                            extensions: ['jpg', 'jpeg', 'png', 'webp'],
                            extensionsMessage: 'Choisis une image JPG, PNG ou WebP.',
                        ),
                    ]),
                ],
            ])
            ->add('youtubeUrl', UrlType::class, [
                'label' => 'Lien de la vidéo YouTube',
                'required' => false,
                'empty_data' => null,
                'help' => 'Colle le lien YouTube normal de la vidéo.',
                'constraints' => [
                    new Url(message: 'Entre une adresse URL valide.'),
                    new Regex(
                        pattern: '~^https?://(www\\.|m\\.)?(youtube\\.com/(watch\\?.*v=|embed/|shorts/|live/)|youtu\\.be/)[A-Za-z0-9_-]{11}([?&].*)?$~i',
                        message: 'Colle un lien vers une vidéo YouTube.',
                    ),
                ],
            ])
            ->add('genre', EntityType::class, [
                'class' => Genre::class,
                'choice_label' => 'nom',
                'label' => 'Catégorie',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Jeux::class,
        ]);
    }
}
