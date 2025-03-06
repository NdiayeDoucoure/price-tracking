<?php

namespace App\DataFixtures;
 
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        $marche = new Marche();
        $marche->setNom('Marché Tilène');
        $marche->setLocalisation('Dakar');
        $marche->setType('urbain');
        $manager->persist($marche);


        $produit1 = new Produit();
        $produit1->setNom('Riz');
        $produit1->setPrix(300.00);
        $produit1->setCategorie('céréales');
        $produit1->setMarche($marche);
        $manager->persist($produit1);


        $produit2 = new Produit();
        $produit2->setNom('Tomate');
        $produit2->setPrix(500.00);
        $produit2->setCategorie('légumes');
        $produit2->setMarche($marche);
        $manager->persist($produit2);

        $manager->flush();
    }
}
