## GUIDE

```bash
docker compose run --rm composer require guzzlehttp/guzzle
```

```bash
docker compose run --rm composer dump-autoload
```

```bash
docker exec -it laravel_composer php artisan make:component Card

docker compose exec app php artisan make:component Card
atau
docker compose run --rm app php artisan make:component Card
```

docker compose exec app php artisan config:clear
php artisan config:clear
