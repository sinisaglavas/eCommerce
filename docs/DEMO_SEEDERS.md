[English](#english) | [Srpski](#srpski)

<a id="english"></a>

# Voltio Demo Seeders

The demo seeders provide fictional but realistic-looking catalog data for local and testing environments. The application reads catalog data from the database; `database/seeders/data/products.php` is used only while seeding.

## Running the seeders

Inside the workspace container, after migrations, run:

```bash
php artisan db:seed
```

`DatabaseSeeder` runs only when `APP_ENV` is `local` or `testing` and calls the individual seeders inside a transaction. Do not use `migrate:fresh` on a database whose data must be preserved.

## Seeded content

- 6 categories and 6 fictional brands.
- 84 products: 14 active products per category, including one out-of-stock product per category.
- 14 rows in each of the 6 specification tables.
- 252 product-image rows: 3 images per product.
- 4 demo users and 9 wishlist entries.

Seeders find records by stable keys such as email, slug, SKU, `product_id`, and image order. Running them again fills missing rows without removing existing data. Existing records are not overwritten unless a seeder explicitly updates them.

## Demo accounts

The password for newly created demo users is `VoltioDemo!2026`.

| Email | Role |
| --- | --- |
| admin@voltio.test | Administrator |
| ana@voltio.test | Customer |
| marko@voltio.test | Customer |
| jelena@voltio.test | Customer |

Do not use demo accounts in production.

## Images

Category-specific local product photographs are stored under `public/images/demo/products/real`. The seeder writes their public paths to `product_images`, so `migrate:fresh --seed` recreates the image records without external network requests. Demo brand marks remain under `public/images/demo/brands`.

## Tables not seeded

Laravel manages migrations, sessions, password-reset tokens, passkeys, cache, locks, and jobs. The seeders do not create fictional operational or security records for those tables.

<a id="srpski"></a>

## Srpski

# Voltio demo seederi

Seed podaci su izmišljeni, sa približno realističnim specifikacijama. Aplikacija ih čita isključivo iz baze. PHP skup u `database/seeders/data/products.php` koristi se samo pri seedovanju.

## Pokretanje

U workspace kontejneru, nakon migracija:

```bash
php artisan db:seed
```

`DatabaseSeeder` dozvoljava izvršavanje samo za `APP_ENV=local` ili `testing` i poziva zasebne seedere u transakciji. Ne koristi `migrate:fresh` na bazi sa podacima koje želiš da sačuvaš.

## Sadržaj na praznoj bazi

- 6 kategorija i 6 izmišljenih brendova.
- 84 proizvoda: 14 aktivnih proizvoda po kategoriji, uključujući po jedan rasprodat proizvod po kategoriji.
- 14 redova u svakoj od 6 tabela specifikacija.
- 252 reda slika: po 3 slike na proizvodu.
- 4 demo korisnika i 9 stavki liste želja.

Seederi traže postojeće zapise po stabilnim ključevima (email, slug, SKU, product_id i redosled slike). Ponovni poziv dopunjava nedostajuće redove, bez prepisivanja postojećih vrednosti. Ne uklanja ranije podatke. Ako raniji katalog koristi iste nazive uz druge slugove, UNIQUE ograničenje može zaustaviti transakciju; prvo uskladiti takve zapise.

## Prijava

Lozinka svih novokreiranih demo korisnika: `VoltioDemo!2026`.

| Email | Uloga |
| --- | --- |
| admin@voltio.test | Administrator |
| ana@voltio.test | Kupac |
| marko@voltio.test | Kupac |
| jelena@voltio.test | Kupac |

Postojećim korisnicima lozinka i privilegije se ne menjaju. Demo naloge ne koristiti u produkciji.

## Slike

`public/images/demo/products/real` sadrži 18 lokalnih fotografija uređaja (3 po kategoriji). Fotografije odgovaraju kategorijama, ali nisu vezane za tačne modele iz kataloga. `public/images/demo/brands` sadrži 6 jednostavnih logotipa demo brendova.

U `product_images.path` se čuvaju direktne javne putanje, npr. `/images/demo/products/real/laptops-1.jpg`, što odgovara trenutnom Product accessor-u i prikazima. Za ove slike nisu potrebni mrežni zahtevi ka spoljnim serverima, `storage:link` ni novi Vite build. `disk` ostaje `public` po postojećoj šemi; ove demo putanje se serviraju direktno iz web public foldera, a nisu relativne Storage putanje za upload.

## Tabele koje se ne seeduju

Laravel upravlja tabelama `migrations`, sesijama, tokenima za reset lozinke, passkeys, kešom, zaključavanjima i redovima poslova. Za njih se ne stvaraju izmišljeni operativni ni bezbednosni zapisi. Tabele porudžbina i korpe još ne postoje u ovoj šemi.
