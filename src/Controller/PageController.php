<?php

namespace App\Controller;

use App\Service\GuideRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PageController extends AbstractController
{
    #[Route('/o-nas', name: 'app_about')]
    public function about(): Response
    {
        return $this->render('page/about.html.twig');
    }

    #[Route('/robots.txt', name: 'app_robots', priority: 10)]
    public function robots(): Response
    {
        $content = "User-agent: *\nAllow: /\nSitemap: https://budolog.pl/sitemap.xml\n";

        return new Response($content, 200, ['Content-Type' => 'text/plain; charset=UTF-8']);
    }

    #[Route('/sitemap.xml', name: 'app_sitemap', priority: 10)]
    public function sitemap(GuideRepository $guides): Response
    {
        $paths = ['/', '/poradniki', '/kalkulatory', '/kalkulatory/ocieplenie', '/kalkulatory/beton', '/kalkulatory/fotowoltaika', '/o-nas'];
        foreach ($guides->all() as $guide) {
            $slug = $guide['slug'] ?? null;
            if (is_string($slug) && $slug !== '') {
                $paths[] = '/poradniki/' . $slug;
            }
        }

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        foreach ($paths as $path) {
            $xml .= '  <url><loc>https://budolog.pl' . htmlspecialchars($path, ENT_XML1) . '</loc></url>' . "\n";
        }
        $xml .= '</urlset>';

        return new Response($xml, 200, ['Content-Type' => 'application/xml; charset=UTF-8']);
    }
}
