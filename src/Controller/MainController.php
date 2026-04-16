<?php

namespace App\Controller;

use App\Dto\Contact;
use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'app_main_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('main/index.html.twig');
    }

    #[Route('/contact', name: 'app_main_contact', methods: ['GET', 'POST'])]
    public function contact(Request $request): Response
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $contact->setSentAt(new \DateTimeImmutable());
            dump($contact);

            return $this->redirectToRoute('app_main_index');
        }

        return $this->render('main/contact.html.twig', [
            'form' => $form,
        ]);
    }
}
