<?php

namespace App\Controller;

use App\Entity\Conference;
use App\Repository\ConferenceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;

#[Route('/conference')]
class ConferenceController extends AbstractController
{
    #[Route('', name: 'app_conference_list', methods: ['GET'])]
    public function list(ConferenceRepository $repository): Response
    {
        $conferences = $repository->findAll();
        $conferences = array_map(
            fn($c) => [
                'id' => $c->getId(),
                'name' => $c->getName(),
                'description' => $c->getDescription(),
            ],
            $conferences
        );

        return $this->json($conferences);
    }

    #[Route('/{id<\d+>}', name: 'app_conference_show', methods: ['GET'])]
    public function show(Conference $conference): Response
    {
        return $this->json([
            'id' => $conference->getId(),
            'name' => $conference->getName(),
            'description' => $conference->getDescription(),
            'startAt' => $conference->getStartAt(),
            'endAt' => $conference->getEndAt(),
        ]);
    }

    #[Route(
        '/{name}/{start}/{end}',
        name: 'app_conference_new',
        requirements: [
            'name' => Requirement::CATCH_ALL,
            'start' => Requirement::DATE_YMD,
            'end' => Requirement::DATE_YMD,
        ]
    )]
    public function newConference(string $name, string $start, string $end, EntityManagerInterface $manager): Response
    {
        $conference = (new Conference())
            ->setName($name)
            ->setDescription('Some generic description')
            ->setAccessible(true)
            ->setStartAt((new \DateTimeImmutable($start))->setTime(0, 0, 0))
            ->setEndAt((new \DateTimeImmutable($end))->setTime(23, 59, 59))
        ;

        $manager->persist($conference);
        $manager->flush();

        return new Response('Conference created');
    }
}
