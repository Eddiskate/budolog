# budolog.pl

Poradniki budowlane + kalkulatory (ocieplenie, beton, fotowoltaika) + afiliacja.
Symfony 6.4, treść w YAML (bez bazy).

## Lokalnie

```bash
composer install
cp .env.example .env.local   # uzupełnij sekrety
php -S 127.0.0.1:8081 -t public
```

## EasyPanel

Zobacz [EASYPANEL.md](EASYPANEL.md).

Pliki deployu (jak w polec-produkt):

- `Dockerfile`
- `docker/entrypoint.sh`
- `.dockerignore`

## Treści

- Poradniki: [`config/guides.yaml`](config/guides.yaml)
- Afiliacja: [`config/affiliates.yaml`](config/affiliates.yaml) — po rejestracji w programach wklej prawdziwe `url`
