<?php

namespace App\Controller;

use App\Service\AffiliateRepository;
use App\Service\GuideRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class GuideController extends AbstractController
{
    public function __construct(
        private readonly GuideRepository $guides,
        private readonly AffiliateRepository $affiliates,
    ) {
    }

    #[Route('/poradniki', name: 'app_guides')]
    public function index(): Response
    {
        return $this->render('guide/index.html.twig', [
            'guides' => $this->guides->all(),
        ]);
    }

    #[Route('/poradniki/{slug}', name: 'app_guide_show')]
    public function show(string $slug): Response
    {
        $guide = $this->guides->findBySlug($slug);
        if ($guide === null) {
            throw $this->createNotFoundException('Poradnik nie został znaleziony');
        }

        $tags = $guide['affiliateTags'] ?? [];
        $tags = is_array($tags) ? array_map('strval', $tags) : [];

        return $this->render('guide/show.html.twig', [
            'guide' => $guide,
            'affiliates' => $this->affiliates->byTags($tags),
        ]);
    }
}
