<?php

namespace App\Controller;

use App\Service\BlogApiClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/artykuly')]
class ArticleController extends AbstractController
{
    public function __construct(
        private readonly BlogApiClient $blogApi,
    ) {
    }

    #[Route('', name: 'app_articles', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('article/index.html.twig', [
            'posts' => $this->blogApi->listPosts(30),
            'apiConfigured' => $this->blogApi->isConfigured(),
        ]);
    }

    #[Route('/{slug}', name: 'app_article_show', methods: ['GET'], requirements: ['slug' => '[a-z0-9\-]+'])]
    public function show(string $slug): Response
    {
        $post = $this->blogApi->getPost($slug);
        if ($post === null) {
            throw $this->createNotFoundException('Nie znaleziono artykułu.');
        }

        return $this->render('article/show.html.twig', [
            'post' => $post,
            'body_html' => (string) ($post['description'] ?? ''),
        ]);
    }
}
