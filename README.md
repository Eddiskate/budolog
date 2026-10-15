# budolog.pl

Poradniki budowlane + kalkulatory (ocieplenie, beton, fotowoltaika) + afiliacja.

Stack: Symfony 6.4, Twig, YAML (bez bazy), Docker / EasyPanel.

## Funkcje

- `/` — home
- `/poradniki` — lista i szczegóły z `config/guides.yaml`
- `/kalkulatory/ocieplenie|beton|fotowoltaika` — kalkulatory JS
- `/o-nas`, `/sitemap.xml`, `/robots.txt`
- bloki afiliacyjne z `config/affiliates.yaml`

## Dev

```bash
composer install
php -S 127.0.0.1:8081 -t public
```

## Deploy

Zobacz [EASYPANEL.md](EASYPANEL.md).
