<?php

namespace App\Controller;

use App\Repository\ConferenceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ConferenceController extends AbstractController
{
    public function __construct(
        private ConferenceRepository $conferenceRepository
    )
    {
        
    }

    #[Route('/', name: 'homepage')]
    public function index(Request $request): Response
    {
        dump($request);

        return $this->render('conference/index.html.twig', [
            'conferences' => $this->conferenceRepository->findAll()
        ]);
    }
}
