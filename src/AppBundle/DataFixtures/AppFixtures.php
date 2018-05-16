<?php
namespace AppBundle\DataFixtures;

use AppBundle\DataFixtures\Faker\BreedProvider;
use AppBundle\Entity\Role;
use AppBundle\Entity\User;
use AppBundle\Entity\Animal;
use AppBundle\Entity\Species;
use AppBundle\Entity\Breed;
use AppBundle\Entity\Category;
use AppBundle\Entity\Product;
use AppBundle\Entity\Task;

use AppBundle\Entity\Weather;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

use Faker;

class AppFixtures extends Fixture
{
    private $encoder;
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }
    public function load(ObjectManager $manager)
    {
        // Role
        $roleUser = new Role();
        $roleUser->setCode('ROLE_USER');
        $roleUser->setLabel('User');

        $roleAdmin = new Role();
        $roleAdmin->setCode('ROLE_ADMIN');
        $roleAdmin->setLabel('Admin');

        // Weather
        $weatherOne = new Weather();
        $weatherOne->setImage('01');
        $weatherOne->setTemperature('15');
        $weatherOne->setMoisture('15');
        $weatherOne->setWindSpeed('15');
        $weatherOne->setCreatedAt(new \DateTime());
        $weatherOne->setUpdatedAt(new \DateTime());


        // User
        $user = new User();
        $user->setUsername('user');
        $user->setEmail('user@oclock.io');
        $user->setAddress('5 rue bidule');
        $user->setCity('Orléans');
        $user->setLastName('dupont');
        $user->setZipCode('45000');
        $user->setSiret('123454');
        $user->setNumberLivestock('456978');
        $user->setPassword($this->encoder->encodePassword($user, 'user'));
        $user->setRole($roleUser);
        $user->setWeather($weatherOne);

        $admin = new User();
        $admin->setUsername('admin');
        $admin->setEmail('user@oclock.io');
        $admin->setAddress('5 rue bidule');
        $admin->setCity('Orléans');
        $admin->setLastName('dupont');
        $admin->setZipCode('45000');
        $admin->setSiret('123454');
        $admin->setNumberLivestock('456978');
        $admin->setPassword($this->encoder->encodePassword($user, 'admin'));
        $admin->setRole($roleAdmin);
        $admin->setWeather($weatherOne);

        $manager->persist($user);
        $manager->persist($admin);

        // Species
        $bovins = new Species();
        $bovins->setWording('bovins');

        $ovins = new Species();
        $ovins->setWording('ovins');

        $porcins = new Species();
        $porcins->setWording('porcins');

        $caprins = new Species();
        $caprins->setWording('caprins');


        $ovinsBreed = $this->ovinsBreed();

        foreach ($ovinsBreed as $value)
        {
            $breed = new Breed();
            $breed->setWording($value);
            $breed->setSpecies($ovins);
            $manager->persist($breed);
        }


        $bovinsBreed = $this->bovinsBreed();

        foreach ($bovinsBreed as $value)
        {
            $breed = new Breed();
            $breed->setWording($value);
            $breed->setSpecies($bovins);
            $manager->persist($breed);
        }

        $porcinsBreed = $this->porcinsBreed();

        foreach ($porcinsBreed as $value)
        {
            $breed = new Breed();
            $breed->setWording($value);
            $breed->setSpecies($porcins);
            $manager->persist($breed);
        }

        $caprinsBreed = $this->caprinsBreed();

        foreach ($caprinsBreed as $value)
        {
            $breed = new Breed();
            $breed->setWording($value);
            $breed->setSpecies($caprins);
            $manager->persist($breed);
        }


        // Category
        $category = new Category();
        $category->setLabel('phytosanitaire');
        $manager->persist($category);

        for ($k = 1; $k < 10; $k++) {
            // Animal
            $product = new Product();
            $product->setLabel('product' .$k);
            $product->setUnit('L');
            $product->setQuantity($k);
            $product->setExpiryDate(new \DateTime('20-03-2020'));
            $product->setUser($user);
            $product->setCategory($category);
            $product->setHasAlert(false);

            $manager->persist($product);
        }

        // Tâche dans le calendrier
        for ($mois=1; $mois < 13; $mois++) {
            for ($tasks=0; $tasks < 50; $tasks++) {
                $task = new Task();
                $task->setTitle('Tâche '.$tasks);
                $task->setMessage('Petite note blabla '.$tasks);
                $date = new \DateTime();
                $date->setDate($date->format('Y'), $mois, rand(1, 28));

                $task->setDay($date);
                $task->setUser($user);
                $manager->persist($task);
            }
        }

        $manager->flush();
    }


    private function ovinsBreed()
    {
        $ovins = [
            'Texel',
            'Suffolk',
            'Boulonnais',
            'Romanov',
            'Charollais',
            'Berrichon',
            'Ile de france',
            'Lacaune',
            'Charmoise',
            'Rouge de l\'Ouest',
            'Bleu du Maine',
            'Hampshire',
            'Autres'
        ];

        return $ovins;
    }

    private function bovinsBreed()
    {
        $bovins = [
            'Prim\'holstein',
            'Montbéliarde',
            'Normande',
            'Rouge flamande',
            'Brune',
            'Jersiaise',
            'Rouge des près',
            'Blanc Bleu Belge',
            'Charolaise',
            'Blonde d\'Aquitaine',
            'Salers',
            'Limousine',
            'Aubrac',
            'Simmental',
            'Hereford',
            'Autres'
        ];

        return $bovins;
    }

    private function porcinsBreed()
    {
        $porcins = [
            'Piétrain',
            'Large White',
            'Landrace Français',
            'Porc Basque',
            'Porc Gascon',
            'Autres'
        ];

        return $porcins;
    }

    private function caprinsBreed()
    {
        $caprins = [
            'Alpine',
            'chèvre de Lorraine',
            'Poitevine',
            'Saanen',
            'Catalane',
            'Autres'
        ];

        return $caprins;
    }
}
