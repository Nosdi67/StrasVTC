<?php

namespace App\Controller;

use App\Entity\Course;
use App\Form\CourseType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Util\Json;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CourseController extends AbstractController
{
    #[Route('/StrasVTC/course/{id}', name: 'app_course')]
    public function index(CourseType $courseType, Course $course = null, Request $request, EntityManagerInterface $entityManagerInterface): Response
    {
        $course = new Course();
        $courseForm = $this->createForm(CourseType::class);
        $courseForm->handleRequest($request);

        $data = json_decode($request->getContent(), true);

        if ($data !== null) {
            $departureAddress = $data['departureAddress'] ?? null;
            $destinationAddress = $data['destinationAddress'] ?? null;
        } else {
            $departureAddress = null;
            $destinationAddress = null;
        }

        if ($courseForm->isSubmitted() && $courseForm->isValid()) {
            $course = $courseForm->getData();
            $entityManagerInterface->persist($course);
            $entityManagerInterface->flush();

            return $this->redirectToRoute('app_confirmationCourse');
        }

        return $this->render('course/index.html.twig', [
            'controller_name' => 'CourseController',
            'courseForm' => $courseForm,
            'departureAddress' => $departureAddress,
            'destinationAddress' => $destinationAddress
        ]);
    }


    #[Route('/StrasVTC/getAdresses', name: 'getAdresses',methods: ['POST'])]
    public function getAdresses(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);//decode the JSON data

        if ($data === null) {
            return new JsonResponse(['error' => 'Invalid JSON'], 400);
        }

        $departureAddress = $data['departureAddress'] ?? null;//recuperation des adresses
        $destinationAddress = $data['destinationAddress'] ?? null;

        if ($departureAddress === null || $destinationAddress === null) {// si la recupération des adresses est null 
            return new JsonResponse(['error' => 'Missing address data'], 400);// alors on renvoie une erreur 400
        }

       
        error_log('Departure Address: ' . $departureAddress);// on log les adresses pour déboguer
        error_log('Destination Address: ' . $destinationAddress);

        // On retourne les adresses en format JSON
        return new JsonResponse([
            'departureAddress' => $departureAddress,
            'destinationAddress' => $destinationAddress,
        ]);
    }




    #[Route('/StrasVTC/ConfirmationDeCourse/{id}', name: 'app_confirmationCourse')]
    public function confirmationCourse(CourseType $courseType, Course $course,EntityManagerInterface $entityManagerInterface): Response
    {
        return $this->render('course/validation.html.twig', [
            'controller_name' => 'CourseController',
        ]);
    }
}