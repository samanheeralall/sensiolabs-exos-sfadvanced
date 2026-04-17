<?php

namespace App\Controller\Api;

use App\Repository\ConferenceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class ConferenceController extends AbstractController
{
    #[Route('/conferences', name: 'app_api_conferences', methods: ['GET'])]
    public function getConferencesApi(Request $request, ConferenceRepository $repository): JsonResponse
    {
        $page = $request->query->getInt('page', 1);
        $limit = 10;

        $conferences = $repository->findBy(
            [],
            limit: $limit,
            offset: ($page - 1) * $limit
        );

        return $this->json($conferences, context: [
            'circular_reference_handler' => fn (object $object) => $object->getId(),
            'groups' => ['api'],
        ]);
    }
}
