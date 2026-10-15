<?php

namespace App\Controller;

use App\Service\AffiliateRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/kalkulatory')]
class CalculatorController extends AbstractController
{
    public function __construct(
        private readonly AffiliateRepository $affiliates,
    ) {
    }

    #[Route('', name: 'app_calculators')]
    public function index(): Response
    {
        return $this->render('calculator/index.html.twig');
    }

    #[Route('/ocieplenie', name: 'app_calc_insulation')]
    public function insulation(): Response
    {
        return $this->render('calculator/insulation.html.twig', [
            'affiliates' => $this->affiliates->byTags(['ocieplenie']),
        ]);
    }

    #[Route('/beton', name: 'app_calc_concrete')]
    public function concrete(): Response
    {
        return $this->render('calculator/concrete.html.twig', [
            'affiliates' => $this->affiliates->byTags(['beton', 'narzedzia']),
        ]);
    }

    #[Route('/fotowoltaika', name: 'app_calc_pv')]
    public function pv(): Response
    {
        return $this->render('calculator/pv.html.twig', [
            'affiliates' => $this->affiliates->byTags(['fotowoltaika', 'energia']),
        ]);
    }
}
