<?php

namespace App\Controller;

use App\Entity\Reservation;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class PaiementController extends AbstractController
{
    #[Route('/paiement', name: 'app_paiement')]
    public function index(): Response
    {
        return $this->render('paiement/index.html.twig', [
            'controller_name' => 'PaiementController',
        ]);
    }
    // PaiementController.php

#[Route('/paiement/{id}', name: 'app_paiement_new')]
public function new(Reservation $reservation): Response
{
    return $this->render('paiement/new.html.twig', [
        'reservation' => $reservation
    ]);
}
    
}
