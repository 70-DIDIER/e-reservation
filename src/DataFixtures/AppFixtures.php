<?php
namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Service;
use App\Entity\Reservation;
use App\Entity\Paiement;
use App\Entity\Avis;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        $prestataires = [];
        $clients = [];

        // 🔹 Création des prestataires
        for ($i = 0; $i < 5; $i++) {
            $user = new User();
            $user->setEmail("prestataire$i@example.com");
            $user->setRoles(['ROLE_PRESTATAIRE']);
            $user->setPassword($this->passwordHasher->hashPassword($user, 'password'));
            $user->setNom($faker->lastName);
            $user->setPrenom($faker->firstName);
            $user->setTelephone($faker->phoneNumber);
            $user->setType('prestataire');
            $manager->persist($user);
            $prestataires[] = $user;
        }

        // 🔹 Création des clients
        for ($i = 0; $i < 10; $i++) {
            $user = new User();
            $user->setEmail("client$i@example.com");
            $user->setRoles(['ROLE_CLIENT']);
            $user->setPassword($this->passwordHasher->hashPassword($user, 'password'));
            $user->setNom($faker->lastName);
            $user->setPrenom($faker->firstName);
            $user->setTelephone($faker->phoneNumber);
            $user->setType('client');
            $manager->persist($user);
            $clients[] = $user;
        }

        $services = [];

        // 🔹 Création des services
        foreach ($prestataires as $prestataire) {
            for ($j = 0; $j < rand(2, 3); $j++) {
                $service = new Service();
                $service->setTitre($faker->jobTitle);
                $service->setCategorie($faker->randomElement(['ménage', 'plomberie', 'coiffure', 'graphisme']));
                $service->setDescription($faker->paragraph);
                $service->setPrix($faker->randomFloat(2, 10, 150));
                $service->setPrestataire($prestataire);
                $manager->persist($service);
                $services[] = $service;
            }
        }

        // 🔹 Réservations, paiements et avis
        foreach ($clients as $client) {
            for ($k = 0; $k < rand(1, 2); $k++) {
                $service = $faker->randomElement($services);

                // Réservation
                $reservation = new Reservation();
                $reservation->setDate($faker->dateTimeBetween('-10 days', '+10 days'));
                $reservation->setClient($client);
                $reservation->setService($service);
                $reservation->setStatut($faker->randomElement(['confirmée', 'en attente', 'annulée']));
                $manager->persist($reservation);

                // Paiement
                $paiement = new Paiement();
                $paiement->setReservation($reservation);
                $paiement->setMontant($service->getPrix());
                $paiement->setMethode($faker->randomElement(['Stripe', 'PayPal']));
                $paiement->setStatut('payé');
                $manager->persist($paiement);

                // Avis (facultatif)
                if ($faker->boolean(70)) {
                    $avis = new Avis();
                    $avis->setClient($client);
                    $avis->setPrestataire($service->getPrestataire());
                    $avis->setNote($faker->numberBetween(3, 5));
                    $avis->setTexte($faker->sentence);
                    $manager->persist($avis);
                }
            }
        }

        $manager->flush();
    }
}
