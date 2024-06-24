<?php

namespace App\Controller;

use App\Entity\Chauffeur;
use App\Repository\ChauffeurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/StrasVTC', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
        #[Route('/StrasVTC/ListeChauffeur', name: 'app_listChauffeur')]
        public function listCourse(ChauffeurRepository $chauffeurRepository): Response{

            $chauffeurs = $chauffeurRepository->findAll();
            return $this->render('home/listChauffeur.html.twig', [
                'chauffeurs' => $chauffeurs,
            ]);
        }
}
