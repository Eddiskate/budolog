<?php

namespace App\Service;

use Symfony\Component\Yaml\Yaml;

class AffiliateRepository
{
    /** @var list<array<string, mixed>>|null */
    private ?array $affiliates = null;

    public function __construct(
        private readonly string $affiliatesFile,
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
     * @param list<string> $tags
     * @return list<array<string, mixed>>
     */
    public function byTags(array $tags): array
    {
        if ($tags === []) {
            return [];
        }

        $set = array_fill_keys($tags, true);

        return array_values(array_filter(
            $this->load(),
            static fn (array $a): bool => isset($set[(string) ($a['tag'] ?? '')])
        ));
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function load(): array
    {
        if ($this->affiliates !== null) {
            return $this->affiliates;
        }

        if (!is_file($this->affiliatesFile)) {
            return $this->affiliates = [];
        }

        $data = Yaml::parseFile($this->affiliatesFile);
        $list = $data['affiliates'] ?? [];

        return $this->affiliates = is_array($list) ? array_values($list) : [];
    }
}
