<?php

namespace App\Controller;

use App\Entity\Service;
use App\Form\ServiceType;
use App\Repository\ServiceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class ServiceController extends AbstractController
{
    #[Route('/service', name: 'app_service')]
    public function index(ServiceRepository $repository): Response
    {
        $user = $this->getUser();
        if (!$user) {
            throw $this->createAccessDeniedException();
        }
    
        // Si l'utilisateur est prestataire, on affiche seulement ses services
        if ($this->isGranted('ROLE_PRESTATAIRE')) {
            $services = $repository->findBy(['prestataire' => $user]);
        } else { // sinon, si c'est un client, on affiche tous les services
            $services = $repository->findAll();
        }
        return $this->render('service/index.html.twig', [
            'services' => $services,
        ]);
    }
    #[Route('/service/create', name: 'app_service_create')]
    #[IsGranted('ROLE_PRESTATAIRE')]
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $service = new Service();
        $user = $this->getUser();
        
        // Associer automatiquement le prestataire
        $service->setPrestataire($user);
        $form = $this->createForm(ServiceType::class, $service);
        $form->handleRequest($request);
        if ($form->isSubmitted() and $form->isValid()){
            $em->persist($service);
            $em->flush();
             return $this->redirectToRoute('app_service');
        }
        return $this->render('service/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
