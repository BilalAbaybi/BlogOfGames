<?php

namespace App\DataFixtures;

use App\Entity\Avis;
use App\Entity\Genre;
use App\Entity\Jeux;
use App\Entity\Utilisateur;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $genres = [];

        foreach ([
            'Simulation',
            'Action',
            'RPG',
            'FPS',
            'Sport',
            'Aventure',
            'Arcade'
        ] as $nom) {

            $genre = new Genre();
            $genre->setNom($nom);

            $manager->persist($genre);

            $genres[$nom] = $genre;
        }

        $utilisateurs = [];

        for ($i = 1; $i <= 15; $i++) {

            $user = new Utilisateur();

            $user->setPseudo($faker->userName());
            $user->setEmail("user$i@test.fr");
            $user->setPassword('test');
            $user->setRoles(['ROLE_USER']);
            $user->setPhotoProfil('default.webp');

            $manager->persist($user);

            $utilisateurs[] = $user;
        }

        $jeux = [];

        $listeJeux = [
            'EA Sports FC 27' => 'Sport',
            'Minecraft' => 'Aventure',
            'Grand Theft Auto V' => 'Action',
            'Red Dead Redemption 2' => 'Aventure',
            'Cyberpunk 2077' => 'RPG',
            'The Witcher 3' => 'RPG',
            'Elden Ring' => 'RPG',
            'God Of War Ragnarok' => 'Action',
            'Fortnite' => 'FPS',
            'Valorant' => 'FPS',
            'League Of Legends' => 'Action',
            'Rocket League' => 'Sport',
            'Counter Strike 2' => 'FPS',
            'Hogwarts Legacy' => 'RPG',
            'Assassins Creed Shadows' => 'Action'
        ];

        foreach ($listeJeux as $titre => $nomGenre) {

            $jeu = new Jeux();

            $jeu->setTitre($titre);
            $jeu->setDescription($faker->text(500));
            $jeu->setDetail($faker->sentence());
            $jeu->setImg('default.webp');

            $jeu->setGalerie([
                'default.webp',
                'default.webp',
                'default.webp'
            ]);

            $jeu->setYoutubeUrl('https://www.youtube.com/watch?v=dQw4w9WgXcQ');

            $jeu->setDateSortie(
                $faker->dateTimeBetween('-10 years', 'now')
            );

            $jeu->setGenre(
                $genres[$nomGenre]
            );

            $manager->persist($jeu);

            $jeux[] = $jeu;
        }


        $titresAvis = [
            'Excellent jeu',
            'Très bon gameplay',
            'Je recommande',
            'Bonne surprise',
            'Pas mal du tout',
            'Décevant',
            'Incroyable expérience',
            'Très immersif',
            'Bon rapport qualité prix',
            'Chef d\'oeuvre'
        ];

        $descriptionsAvis = [
            'Excellent jeu, je recommande fortement.',
            'Très bon gameplay et graphismes.',
            'Je passe des heures dessus.',
            'Une belle surprise.',
            'Quelques bugs mais agréable à jouer.',
            'Très immersif et complet.',
            'Bon rapport qualité prix.',
            'L’histoire est captivante.',
            'Je m’attendais à mieux.',
            'Super expérience multijoueur.',
            'Le contenu est très riche.',
            'Parfait pour jouer entre amis.',
            'La bande son est excellente.',
            'Je recommande à tous les fans du genre.',
            'Un de mes jeux préférés cette année.'
        ];

        $combinaisons = [];

        while (count($combinaisons) < 50) {

            $user = $faker->randomElement($utilisateurs);
            $jeu = $faker->randomElement($jeux);

            $cle = $user->getEmail() . '-' . $jeu->getTitre();

            if (isset($combinaisons[$cle])) {
                continue;
            }

            $combinaisons[$cle] = true;

            $avis = new Avis();

            $avis->setUtilisateur($user);
            $avis->setJeux($jeu);

            $avis->setTitre(
                $faker->randomElement($titresAvis)
            );

            $avis->setDescription(
                $faker->randomElement($descriptionsAvis)
            );

            $avis->setNote(
                $faker->numberBetween(1, 5)
            );

            $manager->persist($avis);
        }

        $manager->flush();
    }
}