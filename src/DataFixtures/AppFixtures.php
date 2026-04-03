<?php

namespace App\DataFixtures;

use AllowDynamicProperties;
use App\Entity\Jeu;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AllowDynamicProperties]
class AppFixtures extends Fixture
{

    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        // Création de mes jeux
        $faker = Faker\Factory::create('fr_FR');
        $genres = ["Artistique", "Stratégie", "Horreur", "Fun", "Enfant"];

        for ($i = 0; $i < 20; $i++) {

            $jeu = new Jeu();
            $jeu->setNom($faker->sentence(3));
            $jeu->setGenre($genres[rand(0, 4)]);
            $jeu->setDateSortie($faker->dateTimeBetween('-10 years'));
            $jeu->setDescription($faker->paragraph(3));
            $jeu->setPrix($faker->numberBetween(10, 100));
            $manager->persist($jeu);

        }

        // Création de mes utilisateurs
        $user = new User();
        $user->setEmail("admin@admin.fr");

        // Hachage du password
        $password = $this->userPasswordHasher->hashPassword($user, "123");

        $user->setPassword($password);
        $user->setRoles(["ROLE_ADMIN"]);
        $user->setPrenom("Thomas");
        $user->setNom("Dubois");
        $manager->persist($user);

        $user = new User();
        $user->setEmail("user@user.fr");

        // Hachage du password
        $password = $this->userPasswordHasher->hashPassword($user, "123");

        $user->setPassword($password);
        $user->setRoles(["ROLE_USER"]);
        $user->setPrenom("Jean");
        $user->setNom("Dupont");
        $manager->persist($user);

        $manager->flush();
    }
}
