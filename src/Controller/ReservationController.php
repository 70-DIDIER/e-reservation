<?php

namespace App\Controller;

use App\Entity\Service;
use App\Entity\Paiement;
use App\Entity\Reservation;
use App\Form\ReservationType;
use App\Form\ReservationStatusType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ReservationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class ReservationController extends AbstractController
{
    #[Route('/reservation', name: 'app_reservation_index')]
    public function index(ReservationRepository $reservationRepository): Response
    {
        $user = $this->getUser();
        $reservations = [];

        if ($this->isGranted('ROLE_CLIENT')) {
            $reservations = $reservationRepository->findByClient($user);
        } elseif ($this->isGranted('ROLE_PRESTATAIRE')) {
            $reservations = $reservationRepository->findByPrestataire($user);
        }

        return $this->render('reservation/index.html.twig', [
            'reservations' => $reservations,
        ]);
    }

    #[Route('/new/{serviceId}', name: 'app_reservation_new')]
    #[IsGranted('ROLE_CLIENT')]
    public function new(
        Request $request,
        EntityManagerInterface $em,
        int $serviceId
    ): Response {
        $service = $em->getRepository(Service::class)->find($serviceId);
        
        if (!$service) {
            throw $this->createNotFoundException('Service non trouvé');
        }

        $reservation = new Reservation();
        $reservation->setClient($this->getUser());
        $reservation->setService($service);
        $reservation->setStatut('en_attente');

        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($reservation);
            $em->flush();

            $this->addFlash('success', 'Réservation créée avec succès');
            return $this->redirectToRoute('app_paiement_new', ['id' => $reservation->getId()]);

        }

        return $this->render('reservation/new.html.twig', [
            'form' => $form->createView(),
            'service' => $service,
        ]);
    }

    #[Route('/{id}/edit-status', name: 'app_reservation_edit_status')]
    #[IsGranted('ROLE_PRESTATAIRE')]
    public function editStatus(
        Request $request,
        Reservation $reservation,
        EntityManagerInterface $em
    ): Response {
        // Vérification que le prestataire est bien propriétaire du service
        if ($reservation->getService()->getPrestataire() !== $this->getUser()) {
            throw $this->createAccessDeniedException('Accès non autorisé');
        }

        $form = $this->createForm(ReservationStatusType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'Statut mis à jour');
            return $this->redirectToRoute('app_reservation_index');
        }

        return $this->render('reservation/edit_status.html.twig', [
            'form' => $form->createView(),
            'reservation' => $reservation,
        ]);
    }

    // pour le paiement
    #[Route('/{id}/payer', name: 'app_reservation_payer')]
public function payer(Reservation $reservation, EntityManagerInterface $em): Response
{
    // Vérifier que la réservation appartient à l'utilisateur
    if ($reservation->getClient() !== $this->getUser()) {
        throw $this->createAccessDeniedException();
    }

    // Créer le paiement
    $paiement = new Paiement();
    $paiement->setMontant($reservation->getService()->getPrix())
        ->setMethode('simulation')
        ->setStatut('payé')
        ->setReservation($reservation);

    // Mettre à jour la réservation
    $reservation->setStatut('confirmée');

    $em->persist($paiement);
    $em->flush();

    $this->addFlash('success', 'Paiement simulé avec succès !');
    return $this->redirectToRoute('app_reservation_index');
    // Après la création réussie de la réservation :
}
}