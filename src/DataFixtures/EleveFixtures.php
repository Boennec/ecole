<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Eleve;
use App\Entity\Classe;

class EleveFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create("fr_FR");

        //créer 3 classes fakées
        for($i=1; $i <= 3; $i++){
        $classe = new Classe();
        $classe->setName(mt_rand(1, 3));

        $manager->persist($classe);

        //créer entre 20 et 25 élèves
        for($j = 1; $j <= mt_rand(20, 25); $j++){
            $eleve = new Eleve();
            $eleve  ->setNom($faker->firstName())
                    ->setPrenom($faker->lastName())
                    ->setDateNaissance($faker->dateTimeInInterval('-14 years', '2 years'))
                    ->setMoyenne(mt_rand(0, 20))
                    ->setAppreciation($faker->paragraph())
                    ->setClasse($classe);

            $manager->persist($eleve);
            }
        }
            $manager->flush();
    }
}
