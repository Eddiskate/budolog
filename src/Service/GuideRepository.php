<?php

namespace App\Service;

use Symfony\Component\Yaml\Yaml;

class GuideRepository
{
    /** @var list<array<string, mixed>>|null */
    private ?array $guides = null;

    public function __construct(
        private readonly string $guidesFile,
    ) {
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function all(): array
    {
        return $this->load();
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function featured(): array
    {
        return array_values(array_filter(
            $this->load(),
            static fn (array $g): bool => !empty($g['featured'])
        ));
    }

    /**
     * @return array<string, mixed>|null
     */
    public function findBySlug(string $slug): ?array
    {
        foreach ($this->load() as $guide) {
            if (($guide['slug'] ?? '') === $slug) {
                return $guide;
            }
        }

        return null;
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function load(): array
    {
        if ($this->guides !== null) {
            return $this->guides;
        }

        if (!is_file($this->guidesFile)) {
            return $this->guides = [];
        }

        $data = Yaml::parseFile($this->guidesFile);
        $list = $data['guides'] ?? [];

        return $this->guides = is_array($list) ? array_values($list) : [];
    }
}
