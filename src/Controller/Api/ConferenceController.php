<?php

namespace App\Controller\Api;

use App\Repository\ConferenceRepository;
use App\Search\ConferenceSearchInterface;
use App\Search\Database\DatabaseConferenceSearch;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

final class ConferenceController extends AbstractController
{
    #[Route('/api/conference', name: 'app_api_conference')]
    public function index(Request $request, #[Autowire(service: DatabaseConferenceSearch::class)] ConferenceSearchInterface $search): JsonResponse
    {
        $page = $request->query->get('page');

        return $this->json($search->searchByName(page: $page), context: [
            AbstractNormalizer::GROUPS => ['api'],
        ]);
    }
}
