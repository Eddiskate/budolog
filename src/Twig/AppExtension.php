<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;

class AppExtension extends AbstractExtension implements GlobalsInterface
{
    public function __construct(
        private readonly string $contactEmail = 'kontakt@budolog.pl',
    ) {
    }

    public function getGlobals(): array
    {
        return [
            'contact_email' => $this->contactEmail,
            'site_name' => 'Budolog',
            'site_url' => 'https://budolog.pl',
        ];
    }
}
