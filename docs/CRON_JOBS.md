[English](#english) | [Srpski](#srpski)

<a id="english"></a>

# Voltio Cron Jobs

## Scheduled order maintenance

The application registers scheduled tasks in `routes/console.php`. The first v2.0 task is `orders:expire-unpaid`, which runs hourly and marks old unpaid orders as `payment_failed`.

## Expiring unpaid orders

An order is eligible when its order status is `awaiting_payment` or `pending`, its payment status is `unpaid`, and it was created at least 48 hours ago.

The command updates both statuses and creates an `order_status_histories` record. Each order is processed in its own database transaction. Running the command again is safe because failed orders no longer match the query.

Run it manually with the default window:

```bash
php artisan orders:expire-unpaid
```

Use a different window when testing:

```bash
php artisan orders:expire-unpaid --hours=24
```

## Running the scheduler

In Docker, keep this process running in a separate terminal:

```bash
docker compose -f compose.dev.yaml exec workspace php artisan schedule:work
```

On a server, configure one system cron entry that runs Laravel's scheduler every minute:

```cron
* * * * * cd /path/to/eCommerce && php artisan schedule:run >> /dev/null 2>&1
```

Laravel then decides when the hourly command is due. Do not register a separate operating-system cron entry for every application command.

## Testing

```bash
php artisan test --compact tests/Feature/CronJobsTest.php
```

The tests verify that old unpaid orders are updated, their history is recorded, recent orders remain unchanged, and invalid hour values fail.

<a id="srpski"></a>

## Srpski

# Voltio cron poslovi

## Automatsko održavanje porudžbina

Aplikacija registruje zakazane zadatke u `routes/console.php`. Prvi v2.0 zadatak je `orders:expire-unpaid`, koji se izvršava svakog sata i stare neplaćene porudžbine označava kao `payment_failed`.

## Isticanje neplaćenih porudžbina

Porudžbina ispunjava uslove kada je njen status `awaiting_payment` ili `pending`, status plaćanja `unpaid`, a kreirana je pre najmanje 48 sati.

Komanda menja oba statusa i upisuje zapis u `order_status_histories`. Svaka porudžbina se obrađuje u sopstvenoj transakciji baze. Ponovno pokretanje je bezbedno jer porudžbine sa statusom `payment_failed` više ne ispunjavaju uslov.

Ručno pokretanje sa podrazumevanim rokom:

```bash
php artisan orders:expire-unpaid
```

Drugi rok, koristan za testiranje:

```bash
php artisan orders:expire-unpaid --hours=24
```

## Pokretanje scheduler-a

U Docker razvojnom okruženju scheduler treba da radi u posebnom terminalu:

```bash
docker compose -f compose.dev.yaml exec workspace php artisan schedule:work
```

Na serveru je dovoljna jedna sistemska cron stavka koja Laravel scheduler pokreće svakog minuta:

```cron
* * * * * cd /putanja/do/eCommerce && php artisan schedule:run >> /dev/null 2>&1
```

Laravel tada odlučuje kada je satni zadatak dospeo. Ne treba praviti posebnu sistemsku cron stavku za svaku aplikacionu komandu.

## Testiranje

```bash
php artisan test --compact tests/Feature/CronJobsTest.php
```

Testovi proveravaju da se stare neplaćene porudžbine promene, da se upiše istorija, da novije porudžbine ostanu iste i da neispravna vrednost za broj sati vrati grešku.
