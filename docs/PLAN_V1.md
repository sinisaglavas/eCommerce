# Voltio — eCommerce CV aplikacija — plan razvoja

## Cilj

Izgraditi Laravel eCommerce aplikaciju za CV/portfolio, s jasno definisanim korisničkim i administratorskim dijelom.

## Verzija 1.0 — trenutni opseg

### Autentikacija

- Registracija korisnika
- Prijava i odjava korisnika

### Katalog

- Kategorije proizvoda
- Lista i detalj proizvoda

### Kupovina

- Korpa
- Kreiranje kupovine/narudžbe
- Pregled "Moje kupovine" za prijavljenog korisnika

### Administracija

- Admin panel
- Upravljanje kategorijama i proizvodima
- Pregled kupovina/narudžbi

## Dizajn i postojeći materijal

Datoteka `resources/html/index.html` je postojeća, još nekomitovana HTML grafika/layout referenca. Čuvamo je netaknutom te je naknadno refaktorišemo u Laravel Blade prikaze i pripadajuće assete.

## Verzija 2.0 — van trenutnog opsega

- Sistem za praćenje isporuke (tracking)
- Cron job za automatska sniženja proizvoda

Ove funkcionalnosti se ne implementiraju u verziji 1.0.
