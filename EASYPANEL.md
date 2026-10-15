# EasyPanel — budolog.pl

## Nowy serwis w EasyPanel

1. **Create App** → typ **App** (Docker).
2. **Source**: Git repo `https://github.com/Eddiskate/budolog.git` (branch `main`).
3. **Build**: Dockerfile (ścieżka: `./Dockerfile`).
4. **Port**: `80` (kontener słucha na 80; EasyPanel wystawi domenę).
5. **Domain**: `budolog.pl` (+ `www` z redirectem jeśli chcesz).
6. **SSL**: Certificate resolver `letsencrypt`, HTTPS włączone, docelowy **HTTP :80**.

## Environment (Environment Variables)

Ustaw w panelu (nie commituj sekretów):

| Key | Przykład |
|-----|----------|
| `APP_ENV` | `prod` |
| `APP_SECRET` | długi losowy string |
| `APP_DEBUG` | `0` |
| `CONTACT_EMAIL` | `kontakt@budolog.pl` |

## Po deployu

1. DNS: A/CNAME domeny na IP / target EasyPanel.
2. Otwórz `https://budolog.pl` — home, poradniki, kalkulatory.
3. W `config/affiliates.yaml` podmień `url` na prawdziwe linki partnerskie.
4. Dopisuj treści w `config/guides.yaml`.

## Lokalnie

```bash
composer install
# skopiuj .env.example → .env.local i uzupełnij
php -S 127.0.0.1:8081 -t public
```

## Afiliacja (poza kodem)

- Zarejestruj się w programach partnerskich (materiały, narzędzia, PV).
- Wklej reflinki do `url` w `config/affiliates.yaml` i zredeployuj.
