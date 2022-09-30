<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Livres;
use App\Entity\User;
use Faker\Factory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\SecurityBundle\Command\UserPasswordEncoderCommand;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class LivreFixture extends Fixture
{
    private $passwordEncoder;
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }
    
    public function load(ObjectManager $manager): void
    {
       
        $faker= \Faker\Factory::create('fr_FR');
        for ($i=0;$i<5;$i++)
        {
            $user = new User();
           
            $user->setNom($faker->lastName())
                ->setPrenom($faker->firstName())
                ->setBirthDate($faker->dateTimeBetween($startDate = '-30 years',
                $endDate = "now"))
                ->setAdresse($faker->streetAddress())
                ->setCodeP($faker->postcode())
                ->setEmail($faker->email())
                ->setPassword($this->passwordEncoder->encodePassword($user,"azerty"))
                ->setAvatar("https://picsum.photos/id/237/200/300");
                $manager->persist($user);
                for($k=0;$k<3;$k++)
                {
                    $category = new Category();
                    $category->setNom($faker->safeColorName());
                    $manager->persist($category);
                    for($j=0;$j<rand(2,4);$j++)
                {
                    $livres = new Livres();
                    $livres->setAuteur($faker->name())
                        ->setTitre($faker->word())
                        ->setDateSortie($faker->dateTimeBetween($startDate = '-30 years',
                        $endDate = "now"));
                    $livres->setUser($user);
                    $livres->setCategory($category);
                    $manager->persist($livres);
                }
                }
        }
        $manager->flush();
    }
}
