# Organizacija koda — Voltio v1.0

Ovaj dokument je zajedničko uputstvo za razvoj jednostavne Laravel eCommerce aplikacije koja se pokreće u Docker-u. Predlozi su polazna tačka, a ne opis trenutno implementiranog koda.

## Način saradnje

- Korisnik sam piše aplikacioni kod.
- Asistent (AI) pomaže objašnjenjima, predlozima organizacije i pregledom koda kada se to zatraži.
- Asistent ne menja aplikacioni kod i ne pokreće instalacije bez izričitog zahteva korisnika.
- Ovaj dokument koristiti kao kontekst za naredne razgovore i ažurirati kada se dogovore nove odluke.

## Trenutno stanje i obim

Prema opisu korisnika, kreirana je početna stranica sa kategorijama i proizvodima, filtriranje po ceni, paginacija i stranica pojedinačnog proizvoda. Promena kategorije kroz navigaciju radi preko JavaScript-a, bez osvežavanja cele stranice.

Taj rad još nije pushovan i nije dostupan u trenutnom lokalnom kodu. Lokalna implementacija nije proverena kao deo ovog dogovora.

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
- `Product` — proizvod, cena i pripadnost kategoriji.
- `Order` — porudžbina korisnika.
- `OrderItem` — pojedinačna stavka porudžbine.

Ako proizvod pripada jednoj kategoriji, dovoljan je `category_id` na proizvodu. Ne uvoditi vezu sa više kategorija dok ne postoji takav zahtev.

Porudžbina pripada korisniku i ima više stavki. Stavka treba da sačuva naziv proizvoda i jediničnu cenu u trenutku kupovine, kao i količinu. Kasnije izmene proizvoda ne smeju da promene istoriju kupovine.

## Korpa i kreiranje porudžbine

Korpa i porudžbina su različite stvari.

Za jednostavnu v1.0 korpu je moguće držati u sesiji, sa ID-em proizvoda i količinom. Trajna korpa u bazi nije potrebna dok ne postoji zahtev, na primer za deljenje korpe između uređaja.

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
