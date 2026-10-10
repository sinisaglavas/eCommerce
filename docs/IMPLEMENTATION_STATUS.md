# Status implementacije

## Trenutno stanje

Storefront funkcionalnost je implementirana na grani `feature/ecommerce-v1`. Aplikacija i dalje koristi privremene PHP objekte i kolekcije (`App\Support\StorefrontCatalog`, `App\Support\StorefrontProductCatalog`) umjesto baze podataka — kategorije, proizvodi, brendovi i popusti se računaju iz tih servisa u memoriji na svakom zahtjevu.

## Implementirano

### Katalog i navigacija

- HTML layout referenca je refaktorisana u Laravel Blade strukturu.
- Kreiran je zajednički storefront layout sa izdvojenim headerom, kategorijskom navigacijom, footerom i SVG ikonama.
- Kategorije dolaze iz `StorefrontCatalog` PHP servisa, a Blade ih prikazuje u navigaciji, na početnoj i u footeru.
- Implementirana je kategorijska ruta `/categories/{category}` i `CategoryController`, sa cjenovnim filterom i paginacijom (12 proizvoda po stranici).
- Implementirana je detaljna stranica proizvoda na ruti `/products/{product}` sa slikom, opisom, dostupnošću, specifikacijama i sekcijom "You might also like" (srodni proizvodi iz iste kategorije).
- Kategorija "Laptops" prošireno sa 6 na 20 proizvoda, svaki sa jedinstvenom (provjereno dostupnom) slikom — ciklično dodjeljivanje slika je zaštićeno modulo operacijom da ne izlazi van granica niza.
- Kartica proizvoda je izdvojena u zajednički partial (`resources/views/partials/product-card.blade.php`) i dijeli se između kategorijskog grida i sekcije srodnih proizvoda.
- Dodani su "Bestseller"/"New" bedževi na odabranim proizvodima i bedž procenta popusta na kartici.
- Dodato wishlist dugme (srce) na kartici proizvoda — vizuelni toggle bez perzistencije (nema baze/korisnika još).

### Pretraga

- Implementirana je `/search` ruta i `SearchController`, sa pretragom po nazivu i brendu proizvoda (`StorefrontProductCatalog::search()`).
- Header sadrži kompaktno polje za pretragu; kucanje (debounced, min. 2 karaktera) otvara dropdown sa do 6 poklapajućih proizvoda (slika, naziv, brend, cijena) ili porukom da nema rezultata.

### Početna stranica

- Početna stranica sada podržava isti AJAX obrazac kao kategorije/proizvodi (`HomeController` vraća JSON kad je zahtjev AJAX, sadržaj je izdvojen u `home/partials/page-content.blade.php`).
- Hero sekcija prikazuje stvarni "Deal of the day" izračunat iz kataloga (`StorefrontProductCatalog::deals()`) — glavna kartica i dvije sporedne kartice se biraju nasumično (shuffle) prilikom svakog učitavanja, sa pravim slikama, cijenama i procentom popusta; sve tri kartice vode na stranicu proizvoda.
- Overlay/kontrast teksta preko fotografija u hero kartama je pojačan (tamniji gradient + text-shadow) da tekst ostane čitljiv bez obzira koja se nasumična slika pojavi.
- "Featured" sekcija (3 kartice) sada vodi na stvarne kategorije (Laptops, Audio, Gaming) umjesto na nepostojeće "#" linkove; kartica "Smart home" (nepostojeća kategorija) zamijenjena je sa "Audio essentials".
- "Popular brands" prikazuje 4 stvarna brenda izračunata iz kataloga (`StorefrontProductCatalog::topBrands()`, grupisano po broju proizvoda), sa stilizovanim "logo mark" inicijalom umjesto lažnih placeholder tekstova.
- Uklonjena newsletter sekcija ("Be the first to hear about deals...") sa početne stranice.
- Top bar iznad headera ("Free shipping...", Stores/Track order/Help) je uklonjen.

### AJAX / UX

- Navigacija između kategorija, proizvoda i početne stranice koristi backend-renderovan Blade HTML preko AJAX-a (`resources/js/updateContent.js`), bez punog osvježavanja stranice.
- URL se ažurira kroz History API; nakon pojednostavljenja, back/forward (popstate) radi konzistentno za sve tri vrste stranica (home/kategorija/proizvod), pošto sve tri sada dijele isti `#page-content` obrazac.
- Klik na "Home" ili bilo koju kategoriju (iz navigacije, kartica, hero deal-ova) automatski skroluje na vrh stranice; ako link sadrži hash (npr. `#categories`), umjesto toga se skroluje do odgovarajuće sekcije.
- Aktivna stavka u kategorijskoj navigaciji se sada ispravno čisti/postavlja i kad se ode na početnu stranicu preko AJAX-a (ranije bi ostala "zaglavljena" na prethodnoj kategoriji).
- Dodan shimmer efekat preko `#page-content` dok je AJAX zahtjev u toku (umjesto da ništa ne signalizira učitavanje).

### Dizajn

- Redizajnirana kategorijska stranica: kompaktan hero banner sa ikonom i nazivom kategorije, paginacija u obliku pune "trake" (Previous/Next na ivicama, brojevi u centru).
- Pretraga u headeru smanjena (manja visina/širina, tih ikonica-dugme umjesto upadljivog plavog "Search" dugmeta).
- Footer je kompaktniji (manji paddinzi, uže liste, manje social ikonice).

### Testovi

- Dodani su PHPUnit feature testovi za kategorije, filter, paginaciju i AJAX odgovore (`tests/Feature/CategoryPageTest.php`), ažurirani da prate prošireni katalog laptopa (20 jedinstvenih slika).

## Tehnička napomena

Zajednički `resources/js/updateContent.js` prima URL i ciljni selektor (primjer: `#page-content`), dohvaća HTML renderovan na backendu i ažurira samo traženi dio stranice. Isti obrazac se sada koristi na sve tri vrste stranica (home, kategorija, proizvod) i na pretrazi.

## Preostalo za verziju 1.0

- Registracija, prijava i odjava korisnika
- Baza, modeli, migracije i administratorsko upravljanje katalogom
- Korpa i kreiranje kupovine/narudžbe
- Pregled "Moje kupovine"
- Admin panel
