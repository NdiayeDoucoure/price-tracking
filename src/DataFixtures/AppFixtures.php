<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use App\Enum\MarcheTypeEnum;
use App\Entity\Marche;
use App\Entity\Produit;
use App\Enum\CategorieEnum;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Entity\User;

class AppFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $passwordHasher)
    {
        
    }
    public function load(ObjectManager $manager): void
    {
        $marche = new Marche();
        $marche->setNom('Marché Tilène');
        $marche->setLocalisation('Dakar');
        $marche->setType(MarcheTypeEnum::URBAIN);
        $manager->persist($marche);

       
        $produit1 = new Produit();
        $produit1->setNom('Riz');
        $produit1->setPrix(300.00);
        $produit1->setCategorie(CategorieEnum::CEREALES);
        $produit1->setMarche($marche);
        $manager->persist($produit1);

        $produit2 = new Produit();
        $produit2->setNom('Tomates');
        $produit2->setPrix(500.00);
        $produit2->setCategorie(CategorieEnum::LEGUMES);
        $produit2->setMarche($marche);
        $manager->persist($produit2);


        $user = new User();
        $user->setEmail('commercant@univ.sn');
        $user->setRoles(['ROLE_COMMERCANT']);
        $user->setPassword($this->passwordHasher->hashPassword($user, 'pass'));
        $manager->persist($user);

        
        $user2 = new User();
        $user2->setEmail('user@univ.sn');
        $user2->setRoles(['ROLE_USER']);
        $user2->setPassword($this->passwordHasher->hashPassword($user2, 'pass'));
        $manager->persist($user2);

        $manager->flush();
    }
}
