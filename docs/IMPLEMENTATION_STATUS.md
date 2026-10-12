# Status implementacije

## Trenutno stanje

Storefront funkcionalnost je implementirana na grani `feature/database-product-catalog`. Kategorije, brendovi, proizvodi, slike i tehničke specifikacije sada dolaze iz MySQL baze kroz Eloquent modele. `DatabaseSeeder` popunjava šest kategorija i 60 demo proizvoda sa Faker podacima, slikama i detaljima specifičnim za kategoriju.

## Implementirano

### Katalog i navigacija

- HTML layout referenca je refaktorisana u Laravel Blade strukturu.
- Kreiran je zajednički storefront layout sa izdvojenim headerom, kategorijskom navigacijom, footerom i SVG ikonama.
- Kategorije dolaze iz tabele `categories`, a Blade ih prikazuje u navigaciji, na početnoj i u footeru.
- Implementirana je kategorijska ruta `/categories/{category}` i `CategoryController`, sa cjenovnim filterom i paginacijom (12 proizvoda po stranici).
- Implementirana je detaljna stranica proizvoda na ruti `/products/{product}` sa slikom, opisom, dostupnošću, specifikacijama i sekcijom "You might also like" (srodni proizvodi iz iste kategorije).
- Proizvodi imaju zajedničke podatke u `products`, galeriju u `product_images`, brend i kategoriju, kao i posebne 1:1 tabele za tehničke specifikacije svake kategorije.
- Kartica proizvoda je izdvojena u zajednički partial (`resources/views/partials/product-card.blade.php`) i dijeli se između kategorijskog grida i sekcije srodnih proizvoda.
- Dodani su "Bestseller"/"New" bedževi na odabranim proizvodima i bedž procenta popusta na kartici.
- Stranica proizvoda prikazuje tehničke specifikacije iz odgovarajuće detaljne tabele.

### Wishlist

- Kreirana je tabela `wishlists` sa jedinstvenim parom `user_id` i `product_id`, pa isti proizvod korisnik može sačuvati samo jednom.
- Prijavljen korisnik može na stranici proizvoda dodati ili ukloniti proizvod iz wishliste AJAX pozivom, bez osvježavanja stranice.
- Header prikazuje stvarni broj sačuvanih proizvoda i dropdown sa do pet posljednje dodatih stavki; JavaScript ga ažurira odmah nakon klika.
- Ruta `/wishlist` prikazuje kompletnu korisnikovu wishlist sa paginacijom. Korpa ostaje predviđena za sesiju.

### Pretraga

- Implementirana je `/search` ruta i `SearchController`, sa pretragom po nazivu i brendu proizvoda iz baze.
- Header sadrži kompaktno polje za pretragu; kucanje (debounced, min. 2 karaktera) otvara dropdown sa do 6 poklapajućih proizvoda (slika, naziv, brend, cijena) ili porukom da nema rezultata.

### Početna stranica

- Početna stranica sada podržava isti AJAX obrazac kao kategorije/proizvodi (`HomeController` vraća JSON kad je zahtjev AJAX, sadržaj je izdvojen u `home/partials/page-content.blade.php`).
- Hero sekcija prikazuje stvarni "Deal of the day" izračunat iz kataloga — glavna kartica i dvije sporedne kartice se biraju nasumično pri svakom učitavanju, sa slikama, cijenama i procentom popusta; sve tri kartice vode na stranicu proizvoda.
- Overlay/kontrast teksta preko fotografija u hero kartama je pojačan (tamniji gradient + text-shadow) da tekst ostane čitljiv bez obzira koja se nasumična slika pojavi.
- "Featured" sekcija (3 kartice) sada vodi na stvarne kategorije (Laptops, Tablets, Smartwatches) umjesto na nepostojeće "#" linkove; kartica "Smart home" (nepostojeća kategorija) zamijenjena je sa "Tablets for every day".
- "Popular brands" prikazuje 4 stvarna brenda izračunata iz baze, grupisana po broju proizvoda, sa stilizovanim "logo mark" inicijalom umjesto lažnih placeholder tekstova.
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
- Administratorsko upravljanje katalogom
- Korpa u sesiji i kreiranje kupovine/narudžbe
- Pregled "Moje kupovine"
- Admin panel
