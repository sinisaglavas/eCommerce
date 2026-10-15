[English](#english) | [Srpski](#srpski)

<a id="english"></a>

# Code Organization — Voltio v1.0

This document is a shared guide for developing the Laravel eCommerce application in Docker. It describes recommended boundaries rather than claiming that every suggestion is already implemented.

## Collaboration

- The user writes the application code.
- The assistant helps with explanations, organization proposals, and code review when requested.
- The assistant changes application code or runs installations only after an explicit request.
- Update this document when the team agrees on a new decision.

## Scope of v1.0

The storefront includes categories, products, price filtering, pagination, and product details. Category changes use JavaScript and avoid a full-page refresh. The catalog is database-backed and includes categories, brands, products, images, and category-specific specification tables. Wishlist data is persistent per user, while the cart is kept in the session.

Version 1.0 covers registration and login, categories and products, the cart, orders, and the admin panel. Tracking and cron jobs remain outside the current scope.

## General approach

Use Laravel's standard structure. Do not add repositories, services, or a separate frontend project for simple operations. Extract logic only when it becomes complex or is repeated.

## Controller responsibilities

| Feature | Controller | Responsibility |
| --- | --- | --- |
| Catalog | `ProductController` | Product lists, category filters, prices, pagination, and details |
| Wishlist | `WishlistController` | Wishlist display and AJAX add/remove operations |
| Cart | `CartController` | Display, add, quantity changes, and removal |
| Orders | `OrderController` | Creating orders and showing the user's orders |
| Admin products | `Admin/ProductController` | Product management |
| Admin categories | `Admin/CategoryController` | Category management |
| Admin orders | `Admin/OrderController` | Order management |

Keep registration and login separate from these controllers. A separate public category controller is enough when categories only filter the catalog.

## Models and relationships

The main models are `User`, `Category`, `Brand`, `Product`, `ProductImage`, the six category-specific detail models, `Wishlist`, `Order`, and `OrderItem`. A product belongs to one category and one brand. Shared fields stay in `products`; typed technical attributes belong in the matching one-to-one detail table. An order stores historical product names, unit prices, and quantities in its items.

## Cart and order creation

The cart and an order are separate concepts. A session cart can store product IDs and quantities until a persistent cross-device cart is needed. Wishlist entries belong to authenticated users and are protected by a unique `user_id`/`product_id` constraint.

At checkout, the server rechecks products, quantities, current prices, and stock. It must not trust totals sent by JavaScript. Creating an order, its items, and stock changes belongs in a database transaction; extract a class such as `CreateOrder` when this workflow becomes complex.

<a id="srpski"></a>

## Srpski

# Organizacija koda — Voltio v1.0

Ovaj dokument je zajedničko uputstvo za razvoj jednostavne Laravel eCommerce aplikacije koja se pokreće u Docker-u. Predlozi su polazna tačka, a ne opis trenutno implementiranog koda.

## Način saradnje

- Korisnik sam piše aplikacioni kod.
- Asistent (AI) pomaže objašnjenjima, predlozima organizacije i pregledom koda kada se to zatraži.
- Asistent ne menja aplikacioni kod i ne pokreće instalacije bez izričitog zahteva korisnika.
- Ovaj dokument koristiti kao kontekst za naredne razgovore i ažurirati kada se dogovore nove odluke.

## Trenutno stanje i obim

Prema opisu korisnika, kreirana je početna stranica sa kategorijama i proizvodima, filtriranje po ceni, paginacija i stranica pojedinačnog proizvoda. Promena kategorije kroz navigaciju radi preko JavaScript-a, bez osvežavanja cele stranice.

Katalog je sada povezan sa bazom: postoje kategorije, brendovi, proizvodi, slike i zasebne tabele tehničkih specifikacija. Wishlist je trajna lista po korisniku, dok je korpa namerno ostavljena u sesiji.

**v1.0 obuhvata:**

- Registraciju i prijavu.
- Kategorije i proizvode.
- Korpu.
- Moje kupovine.
- Admin panel.

Tracking i cron job ostaju za v2.0. Za sada ne uvoditi dodatne slojeve samo zbog buduće verzije.

## Osnovni pristup

Koristiti standardnu Laravel strukturu. Nisu potrebni repositories i services za svaku sitnicu, niti zaseban frontend projekat samo zato što se kategorije menjaju preko JavaScript-a.

Logiku izdvajati kada postane složena ili se ponavlja, a ne unapred.

## Podela kontrolera

| Funkcionalnost | Predloženi kontroler | Odgovornost |
| --- | --- | --- |
| Katalog | `ProductController` | Lista, kategorija, cena, paginacija i detalji proizvoda |
| Wishlist | `WishlistController` | Prikaz liste i AJAX dodavanje/uklanjanje sačuvanog proizvoda |
| Korpa | `CartController` | Prikaz, dodavanje, promena količine i uklanjanje |
| Kupovine | `OrderController` | Kreiranje porudžbine i prikaz sopstvenih kupovina |
| Admin proizvodi | `Admin/ProductController` | Upravljanje proizvodima |
| Admin kategorije | `Admin/CategoryController` | Upravljanje kategorijama |
| Admin porudžbine | `Admin/OrderController` | Upravljanje porudžbinama |

Registraciju i prijavu držati odvojeno od ovih kontrolera. Poseban javni kontroler za kategorije nije potreban ako kategorije služe samo kao filter kataloga.

## Modeli i veze

Za početak su dovoljni:

- `User` — korisnik i njegove porudžbine.
- `Category` — kategorija proizvoda.
- `Brand` — brend koji može imati proizvode u više kategorija.
- `Product` — zajednički podaci proizvoda, cena i pripadnost jednoj kategoriji.
- `ProductImage` — uređena galerija slika proizvoda.
- `LaptopDetail`, `SmartphoneDetail`, `TelevisionDetail`, `MonitorDetail`, `TabletDetail` i `SmartwatchDetail` — 1:1 tehničke specifikacije proizvoda, zavisno od kategorije.
- `Wishlist` — trajno sačuvan proizvod korisnika; kombinacija `user_id` i `product_id` mora biti jedinstvena.
- `Order` — porudžbina korisnika.
- `OrderItem` — pojedinačna stavka porudžbine.

Ako proizvod pripada jednoj kategoriji, dovoljan je `category_id` na proizvodu. Ne uvoditi vezu sa više kategorija dok ne postoji takav zahtev.

Katalog koristi šest kategorija: Laptops, Smartphones, TVs, Monitors, Tablets i Smartwatches. Zajednički podaci ostaju u `products`, dok svaka od njih ima zasebnu tabelu sa tipiziranim tehničkim atributima i jedinstvenim `product_id`. Time se izbegava jedna velika tabela sa mnogo nepovezanih i praznih kolona.

Porudžbina pripada korisniku i ima više stavki. Stavka treba da sačuva naziv proizvoda i jediničnu cenu u trenutku kupovine, kao i količinu. Kasnije izmene proizvoda ne smeju da promene istoriju kupovine.

## Korpa i kreiranje porudžbine

Korpa i porudžbina su različite stvari.

Za jednostavnu v1.0 korpu je moguće držati u sesiji, sa ID-em proizvoda i količinom. Trajna korpa u bazi nije potrebna dok ne postoji zahtev, na primer za deljenje korpe između uređaja.

Wishlist je odvojena od korpe i čuva se u bazi za prijavljenog korisnika. Dodavanje i uklanjanje može biti AJAX zahtev, ali server mora proveriti prijavljenog korisnika i sprečiti duplikate jedinstvenim ograničenjem baze.

Kada korisnik potvrdi kupovinu, server proverava proizvode, količine, aktuelne cene i dostupnost, pa kreira `Order` i njegove `OrderItem` stavke. Ne oslanjati se na cenu ili ukupan iznos koji pošalje JavaScript.

Kada ovaj postupak obuhvati više povezanih koraka, ima smisla izdvojiti jednu klasu, na primer `CreateOrder`. Čuvanje porudžbine, stavki i eventualno skidanje zaliha izvršavati u transakciji baze.

## Odgovornosti delova aplikacije

- **Rute:** povezuju URL sa kontrolerom i odgovarajućim middleware-om.
- **Kontroleri:** obrađuju zahtev i vraćaju odgovor. Validaciju izdvojiti u `FormRequest` klase kada se obrazac proširi.
- **Modeli:** definišu veze i ponovljive upite, poput filtriranja proizvoda.
- **Blade prikazi:** prikazuju podatke; ne izvršavaju upite u bazu.
- **JavaScript:** upravlja interakcijama i osvežavanjem prikaza.
- **Server:** proverava cene, dostupnost i dozvole. Korisnik vidi samo svoje kupovine, a admin funkcije moraju biti zaštićene na serveru.

## Filteri, paginacija i JavaScript

Kategoriju, cenu i stranicu predstavljati query parametrima URL-a, na primer:

```text
/products?category=3&min_price=1000&max_price=5000&page=2
```

Iste parametre i istu serversku logiku filtriranja koristiti za početno učitavanje i JavaScript zahteve. Ne praviti dve odvojene implementacije filtera.

Pri promeni filtera kroz JavaScript ažurirati URL kako bi osvežavanje stranice i deljenje linka zadržali izabrane filtere. Kada se promeni kategorija ili raspon cena, vratiti paginaciju na prvu stranicu.

Način odgovora za JS zahteve dogovoriti sa kolegom na osnovu njegovog postojećeg rešenja; nije potrebno prepravljati funkcionalan prikaz bez konkretnog razloga.

## Organizacija Blade prikaza

Predložena struktura unutar `resources/views/`:

```text
layouts/
components/
products/
cart/
orders/
admin/
```

Zajednički raspored stranice držati u layout-u. Delove koji se ponavljaju, poput kartice proizvoda, izdvojiti u komponente. Ne praviti komponentu za svaki mali deo prikaza ako to ne olakšava rad.

## Otvorena odluka pre implementacije kupovine

Potrebno je dogovoriti da li v1.0 podrazumeva pravo online plaćanje ili samo potvrdu porudžbine, na primer plaćanje pouzećem.

Ova odluka još nije doneta i značajno utiče na složenost procesa kupovine.
