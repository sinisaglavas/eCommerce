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

`public/images/demo/products` sadrži 18 lokalnih SVG ilustracija uređaja (3 po kategoriji), izvedenih iz postojećih ikonica aplikacije. Ilustracije su deljene između proizvoda iste kategorije i nisu fotografije tačnih modela. `public/images/demo/brands` sadrži 6 jednostavnih logotipa demo brendova.

U `product_images.path` se čuvaju direktne javne putanje, npr. `/images/demo/products/laptops-1.svg`, što odgovara trenutnom Product accessor-u i prikazima. Za ove slike nisu potrebni mrežni zahtevi ka spoljnim serverima, `storage:link` ni novi Vite build. `disk` ostaje `public` po postojećoj šemi; ove demo putanje se serviraju direktno iz web public foldera, a nisu relativne Storage putanje za upload.

## Tabele koje se ne seeduju

Laravel upravlja tabelama `migrations`, sesijama, tokenima za reset lozinke, passkeys, kešom, zaključavanjima i redovima poslova. Za njih se ne stvaraju izmišljeni operativni ni bezbednosni zapisi. Tabele porudžbina i korpe još ne postoje u ovoj šemi.
