<?php

namespace App\Controller;

use App\Repository\ServiceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(ServiceRepository $repository): Response
    {
        
            $services = $repository->findAll();
        

        return $this->render('home/index.html.twig', [
            'services' => $services,
        ]);
    }
       
}
