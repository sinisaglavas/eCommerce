# Status implementacije

## Trenutno stanje

Početna storefront funkcionalnost je implementirana na grani `feature/ecommerce-v1`. Aplikacija još koristi privremene PHP objekte i kolekcije umjesto baze podataka.

## Implementirano

- HTML layout referenca je refaktorisana u Laravel Blade strukturu.
- Kreiran je zajednički storefront layout sa izdvojenim headerom, kategorijskom navigacijom, footerom i SVG ikonama.
- Dodana je početna ruta i `HomeController`.
- Kategorije dolaze iz `StorefrontCatalog` PHP servisa, a Blade ih prikazuje u navigaciji, na početnoj i u footeru.
- Implementirana je kategorijska ruta `/categories/{category}` i `CategoryController`.
- Svaka od šest kategorija ima šest privremenih proizvoda, slike, cijene i eventualnu staru cijenu.
- Dodani su cjenovni filter i paginacija na kategorijskim stranicama.
- Implementirana je detaljna stranica proizvoda na ruti `/products/{product}` sa slikom, opisom, dostupnošću i specifikacijama.
- Navigacija između kategorija i prikaza proizvoda koristi backend-renderovan Blade HTML preko AJAX-a, bez punog osvježavanja stranice.
- URL se ažurira kroz History API, pa se kategorije i proizvodi mogu direktno otvoriti, kopirati i koristiti preko Back/Forward navigacije.
- Dodani su PHPUnit feature testovi za kategorije, filter, paginaciju i AJAX odgovore.

## Tehnička napomena

Zajednički `resources/js/updateContent.js` prima URL i ciljni selektor (primjer: `#page-content`), dohvaća HTML renderovan na backendu i ažurira samo traženi dio stranice. Ovaj obrazac se može koristiti i na narednim interaktivnim ekranima.

## Preostalo za verziju 1.0

- Registracija, prijava i odjava korisnika
- Baza, modeli, migracije i administratorsko upravljanje katalogom
- Korpa i kreiranje kupovine/narudžbe
- Pregled "Moje kupovine"
- Admin panel
