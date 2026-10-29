<?php

namespace App\Controller;

use App\Service\BlogApiClient;
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
    public function sitemap(GuideRepository $guides, BlogApiClient $blogApi): Response
    {
        $today = (new \DateTimeImmutable('today'))->format('Y-m-d');
        $entries = [
            ['/', '1.0', 'weekly'],
            ['/poradniki', '0.9', 'weekly'],
            ['/artykuly', '0.9', 'weekly'],
            ['/kalkulatory', '0.9', 'weekly'],
            ['/kalkulatory/ocieplenie', '0.8', 'monthly'],
            ['/kalkulatory/beton', '0.8', 'monthly'],
            ['/kalkulatory/fotowoltaika', '0.8', 'monthly'],
            ['/o-nas', '0.5', 'monthly'],
        ];
        foreach ($guides->all() as $guide) {
            $slug = $guide['slug'] ?? null;
            if (is_string($slug) && $slug !== '') {
                $entries[] = ['/poradniki/' . $slug, '0.8', 'monthly'];
            }
        }
        foreach ($blogApi->listPosts(100) as $post) {
            $slug = $post['slug'] ?? null;
            if (is_string($slug) && $slug !== '') {
                $entries[] = ['/artykuly/' . $slug, '0.7', 'weekly'];
            }
        }

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        foreach ($entries as [$path, $priority, $changefreq]) {
            $loc = htmlspecialchars('https://budolog.pl' . $path, ENT_XML1);
            $xml .= "  <url>\n";
            $xml .= "    <loc>{$loc}</loc>\n";
            $xml .= "    <lastmod>{$today}</lastmod>\n";
            $xml .= "    <changefreq>{$changefreq}</changefreq>\n";
            $xml .= "    <priority>{$priority}</priority>\n";
            $xml .= "  </url>\n";
        }
        $xml .= '</urlset>';

        return new Response($xml, 200, ['Content-Type' => 'application/xml; charset=UTF-8']);
    }
}
