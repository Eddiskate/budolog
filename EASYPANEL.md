# EasyPanel — budolog.pl

## Nowy serwis

1. **Create App** → typ **App** (Docker).
2. **Source**: Git repo z tym projektem (branch `main`).
3. **Build**: Dockerfile.
4. **Port**: `80`.
5. **Domain**: `budolog.pl` (+ `www` jeśli chcesz).
6. **SSL**: resolver `letsencrypt`, HTTPS włączone, docelowy HTTP:80.

## Environment

| Key | Przykład |
|-----|----------|
| `APP_ENV` | `prod` |
| `APP_SECRET` | długi losowy string |
| `APP_DEBUG` | `0` |
| `CONTACT_EMAIL` | `kontakt@budolog.pl` |

## Po deployu

1. DNS: rekord A `budolog.pl` → IP EasyPanel.
2. Otwórz `https://budolog.pl`.
3. W `config/affiliates.yaml` podmień `url` na prawdziwe linki partnerskie.
4. Dopisuj poradniki w `config/guides.yaml`.

## Lokalnie

```bash
composer install
cp .env.example .env.local   # opcjonalnie
php -S 127.0.0.1:8081 -t public
```
