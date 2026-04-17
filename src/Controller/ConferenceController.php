<?php

namespace App\Controller;

use App\Entity\Conference;
use App\Form\ConferenceType;
use App\Registry\ConferenceAttributes;
use App\Search\ConferenceSearchInterface;
use App\Search\Database\DatabaseConferenceSearch;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/conference')]
class ConferenceController extends AbstractController
{
    #[Route('', name: 'app_conference_list', methods: ['GET'])]
    public function list(Request $request, #[Autowire(service: DatabaseConferenceSearch::class)] ConferenceSearchInterface $search): Response
    {
        $page = $request->query->get('page');
        $name = $request->query->get('name');
        $conferences = $search->searchByName($name, $page);

        return $this->render('conference/list.html.twig', [
            'conferences' => $conferences,
        ]);
    }

    #[Route('/conference/search', name: 'app_conference_search', methods: ['GET'])]
    public function search(Request $request, ConferenceSearchInterface $search): Response
    {
        $page = $request->query->get('page');
        $name = $request->query->get('name');

        return $this->render('conference/list.html.twig', [
            'conferences' => $search->searchByName($name, $page)
        ]);
    }

    #[Route('/{id<\d+>}', name: 'app_conference_show', methods: ['GET'])]
    public function show(Conference $conference): Response
    {
        return $this->render('conference/show.html.twig', [
            'conference' => $conference,
        ]);
    }

    #[IsGranted(new Expression("is_granted('ROLE_ORGANIZER') or is_granted('ROLE_WEBSITE')"))]
    #[Route('/new', name: 'app_conference_new', methods: ['GET', 'POST'])]
    #[Route('/{id<\d+>}/edit', name: 'app_conference_edit', methods: ['GET', 'POST'])]
    public function newConference(?Conference $conference, Request $request, EntityManagerInterface $manager): Response
    {
        if ($conference instanceof Conference) {
            $this->denyAccessUnlessGranted(ConferenceAttributes::EDIT_CONF, $conference);
        }

        $conference ??= new Conference();
        $form = $this->createForm(ConferenceType::class, $conference);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (null === $conference->getId()) {
                $conference->setCreatedBy($this->getUser());
            }
            $manager->persist($conference);
            $manager->flush();

            return $this->redirectToRoute('app_conference_show', ['id' => $conference->getId()]);
        }

        return $this->render('conference/new.html.twig', [
            'form' => $form,
        ]);
    }
}
