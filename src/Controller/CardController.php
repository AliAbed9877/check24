<?php

namespace App\Controller;

use App\Form\CardChangeType;
use App\Service\CardService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class CardController extends AbstractController
{
    /**
     * show a greeting page
     */
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('card/index.html.twig');
    }

    /*
     * this router show the list of the cards
     */
    #[Route('/cards', name: 'app_cards')]
    public function list(Request $request, CardService $cardService): Response
    {
        $sort = $request->query->get('sort', 'annualFee');
        $direction = $request->query->get('direction', 'ASC');
        $cards = $cardService->getCards([], $sort, $direction);
        return $this->render('card/list.html.twig', [
            'cards'            => $cards,
            'currentSort'      => $sort,
            'currentDirection' => $direction
        ]);
    }

    /*
      * This handles both the edit form view and submission
      */
    #[Route('/card/edit/{id}', name: 'app_card_edit', methods: ['GET', 'POST'])]
    public function edit(int $id, Request $request, CardService $cardService): Response
    {
        $card = $cardService->findCardById($id);
        $form = $this->createForm(CardChangeType::class);
        $form->handleRequest($request);
        $cardChanges = $cardService->getChangesForCard($id);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $cardService->updateCardDetails($id,$data);
            $this->addFlash('success', 'Card Change added successfully!');
            return $this->redirectToRoute('app_cards');
        }

        return $this->render('card/edit.html.twig', [
            'card' => $card,
            'form' => $form->createView(),
            'cardChanges' => $cardChanges
        ]);
    }
}
