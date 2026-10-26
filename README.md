[English](#english) | [Srpski](#srpski)

<a id="english"></a>

# Voltio

A Laravel application for reserving and purchasing products. Customers will be able to browse products by category, keep selected products in a shopping cart, and view their purchase history.

## Project Status

The project is in development. The Docker development environment is running, and the features below describe the current release scope.

## Version 1.0 — Core Features

- **Registration and Login:** Create an account and sign in.
- **Categories:** Browse products by category.
- **Products:** View available products and their details.
- **Shopping Cart:** Keep selected products in a cart and manage the selection before reserving or purchasing them.
- **Reservations and Purchases:** Reserve or purchase selected products.
- **My Purchases:** View purchase history.
- **Admin Panel:** Provide an administrative interface for managing the application.

## Version 2.0 — Additional Features

Version 2.0 includes all features from Version 1.0 and adds:

- **Tracking:** Customers can view status history, payment status, shipping details, and delivery dates for their orders.
- **Cron Jobs:** The hourly `orders:expire-unpaid` command marks unpaid orders older than 48 hours as payment failed.

## Development Environment

The application uses Laravel and PHP, with a Docker Compose development environment that includes Nginx, PHP-FPM, MySQL, Redis, phpMyAdmin, a workspace service, and a migration service.

The development configuration is defined in `compose.dev.yaml`.

### Start the Configured Development Environment

From the project root, run:

```bash
docker compose -f compose.dev.yaml up -d --build
```

Open the application at [http://localhost:8080](http://localhost:8080).

### Check Container Status

```bash
docker compose -f compose.dev.yaml ps -a
```

### Stop the Development Environment

```bash
docker compose -f compose.dev.yaml stop
```
### Run the scheduler locally

Keep the scheduler running in a separate workspace terminal:

```bash
docker compose -f compose.dev.yaml exec workspace php artisan schedule:work
```

See [docs/CRON_JOBS.md](docs/CRON_JOBS.md) for command details, tests, and production setup.

### Refresh migration + re-seeder initialization
```bash
docker compose -f compose.dev.yaml run --rm workspace php artisan migrate:fresh --seed
```

### View all tables related to the application
```bash
docker compose -f compose.dev.yaml exec mysql   mysql -ularavel -ppassword app -e "SHOW TABLES;"
```

### Clearing the Docker build cache can free up several gigabytes (safe cleaning)
```bash
docker builder prune -af
```

### Removing stalled containers:
```bash
docker container prune

```

### Removing an image that is not being used by any active container:
```bash
docker image prune -a
```

<a id="srpski"></a>

## Srpski

# Voltio

Laravel aplikacija za rezervisanje i kupovinu proizvoda. Korisnici mogu da pregledaju proizvode po kategorijama, čuvaju proizvode u korpi i prate istoriju svojih porudžbina.

## Status projekta

Projekat je u razvoju. Docker razvojno okruženje je aktivno, a funkcionalnosti opisane u nastavku predstavljaju trenutni obim aplikacije.

## Verzija 1.0 — Osnovne funkcionalnosti

- **Registracija i prijava:** Kreiranje naloga i prijavljivanje korisnika.
- **Kategorije:** Pregled proizvoda po kategorijama.
- **Proizvodi:** Pregled dostupnih proizvoda i njihovih detalja.
- **Korpa:** Čuvanje izabranih proizvoda i menjanje izbora pre kupovine.
- **Rezervacije i kupovine:** Rezervisanje ili kupovina izabranih proizvoda.
- **Moje kupovine:** Pregled istorije porudžbina.
- **Admin panel:** Administratorsko upravljanje aplikacijom.

## Verzija 2.0 — Dodatne funkcionalnosti

Verzija 2.0 obuhvata sve funkcionalnosti verzije 1.0 i dodaje:

- **Praćenje porudžbine:** Korisnik vidi istoriju statusa, status plaćanja, podatke o dostavi i datume isporuke.
- **Cron poslovi:** Satna komanda `orders:expire-unpaid` označava neplaćene porudžbine starije od 48 sati kao neuspešne za plaćanje.

## Razvojno okruženje

Aplikacija koristi Laravel i PHP, sa Docker Compose okruženjem koje uključuje Nginx, PHP-FPM, MySQL, Redis, phpMyAdmin, workspace servis i servis za migracije.

Konfiguracija razvojnog okruženja nalazi se u fajlu `compose.dev.yaml`.

### Pokretanje Docker okruženja

Iz glavnog foldera projekta pokrenuti:

```bash
docker compose -f compose.dev.yaml up -d --build
```

Aplikacija je dostupna na [http://localhost:8080](http://localhost:8080).

### Provera kontejnera

```bash
docker compose -f compose.dev.yaml ps -a
```

### Zaustavljanje okruženja

```bash
docker compose -f compose.dev.yaml stop
```

### Pokretanje scheduler-a lokalno

Scheduler treba da radi u posebnom terminalu workspace kontejnera:

```bash
docker compose -f compose.dev.yaml exec workspace php artisan schedule:work
```

Detalji o komandama, testovima i produkcionom podešavanju nalaze se u dokumentu [docs/CRON_JOBS.md](docs/CRON_JOBS.md).

### Osvežavanje migracija i seed podataka

```bash
docker compose -f compose.dev.yaml run --rm workspace php artisan migrate:fresh --seed
```

### Pregled tabela aplikacije

```bash
docker compose -f compose.dev.yaml exec mysql mysql -ularavel -ppassword app -e "SHOW TABLES;"
```

### Oslobađanje Docker build keša

```bash
docker builder prune -af
```

### Uklanjanje zaustavljenih kontejnera

```bash
docker container prune
```

### Uklanjanje nekorišćene slike

```bash
docker image prune -a
```
