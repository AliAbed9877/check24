<?php

namespace App\Controller;

use App\Service\CardService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class CardController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('card/index.html.twig');
    }
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

    #[Route('/card/edit/{id}', name: 'app_card_edit_form', methods: ['GET'])]
    public function editForm(int $id, CardService $cardService): Response
    {

        $card = $cardService->findCardById($id);
        return $this->render('card/edit.html.twig', [
            'card' => $card,
        ]);
    }

    #[Route('/card/edit/{id}', name: 'app_card_edit', methods: ['POST'])]
    public function edit(int $id, Request $request, CardService $cardService): Response
    {
        $data = $request->request->all();
        $cardService->updateCardDetails($id, $data);
        return $this->redirectToRoute('app_cards');
    }
}
