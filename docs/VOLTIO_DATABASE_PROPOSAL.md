[English](#english) | [Srpski](#srpski)

<a id="english"></a>

# Voltio — Database Proposal

## Database proposal

### Voltio • Laravel / MySQL • v1.0

This is an implementation proposal for a central product catalog, separate brands, 2–4 images per published product, stock tracking, and numeric specification filters.

### Decisions used throughout the proposal

Each product belongs to one category and one brand. RAM, screen size, and other filterable values receive dedicated columns in `products`; descriptive extras may use JSON. Money is stored as integer cents with `_cents` column names and EUR as the catalog currency. Every sellable model has its own SKU, price, and stock row.

### Table overview

| Table | Purpose |
| --- | --- |
| users | Existing users and administrator access |
| categories | Category names and presentation data |
| brands | Brands, logos, and descriptions |
| products | Shared product data, specifications, and stock |
| product_images | Product gallery and image ordering |
| user_addresses | Saved user addresses |
| orders | Order and customer snapshot at purchase time |
| order_items | Quantities and historical prices |

The cart remains in the session as `product_id` and `quantity`. Prices are rechecked on the server. Tracking and cron jobs are implemented in v2.0.

### Relationships and rules

```text
categories  1 ─── N products N ─── 1 brands
products    1 ─── N product_images
users       1 ─── N user_addresses
users       1 ─── N orders
orders      1 ─── N order_items
products    1 ─── N order_items
```

Use `BIGINT UNSIGNED` IDs, matching foreign-key types, timestamps, and `snake_case` names. `NULL` means unknown or not applicable, not zero. Restrict deletion of categories and brands with products, cascade product images, preserve order history, and prefer inactive products or cancelled orders over physical deletion.

Do not store derived values such as category product counts, discount percentages, or availability. Calculate them from relationships, prices, `is_active`, and stock. Store an order total as a historical calculation.

### Category-specific specifications

Keep common fields in `products` and use one-to-one tables for laptops, smartphones, televisions, monitors, tablets, and smartwatches. This keeps RAM, diagonal, storage, and similar filter values typed and searchable without a large table full of unrelated nullable columns.

<a id="srpski"></a>

## Srpski

# Voltio — predlog baze podataka

## Predlog baze podataka

### Voltio • Laravel / MySQL • v1.0

Radni predlog za implementaciju. Dokument ne opisuje već kreiranu bazu. Usvojeni zahtevi: centralni katalog proizvoda, posebni brendovi, 2–4 slike po objavljenom proizvodu, praćenje zaliha i numerički filteri za specifikacije.

### Odluke koje koristimo u celom predlogu

Jedan proizvod pripada jednoj kategoriji i jednom brendu. RAM, dijagonala i druge vrednosti za filtriranje dobijaju posebne kolone u products. Dodatni opisni podaci mogu biti u JSON-u; ključni filteri ne zavise od JSON-a.

Novac čuvamo kao celobrojni broj centi, uz nazive kolona koji se završavaju sa _cents. Valuta kataloga je EUR. Primer: 42900 = 429,00 €. Dijagonala koristi DECIMAL, ne tekst „27 inches“.

Svaki prodajni model ima svoj SKU i red u products. Laptop sa 16 GB i isti model sa 32 GB predstavljaju dva proizvoda sa zasebnim SKU, cenom i zalihom. Izbor varijanti na jednoj stranici nije deo ovog modela.

### Pregled tabela

| Tabela | Uloga |
| --- | --- |
| users | Postojeći korisnici i administratorski pristup |
| categories | Kategorije i njihov izgled |
| brands | Brendovi, logo i opis |
| products | Osnovni podaci, specifikacije i zalihe |
| product_images | Galerija i redosled slika |
| user_addresses | Sačuvane adrese korisnika |
| orders | Porudžbina i podaci kupca u trenutku kupovine |
| order_items | Stavke, količine i istorijske cene |

Korpa ostaje u sesiji: product_id i quantity. Cene se ponovo proveravaju na serveru. Korpa između uređaja zahteva dodatne tabele i nije pretpostavka ovog predloga.

Tracking i cron job su implementirani u v2.0. Predlog obuhvata podatke za status plaćanja, ali izbor online procesora plaćanja još nije dogovoren.

## Veze, nazivi i osnovna pravila

### Dijagram veza

```text
categories  1 ─── N products N ─── 1 brands
products    1 ─── N product_images
users       1 ─── N user_addresses
users       1 ─── N orders
orders      1 ─── N order_items
products    1 ─── N order_items
```

1 → N znači: jedan zapis može imati više povezanih zapisa. order_items povezuje porudžbinu i proizvod, ali čuva i sopstvene istorijske podatke.

### Konvencije za sve migracije

Primarni ključ id: BIGINT UNSIGNED. Strani ključevi moraju biti istog tipa. Sve tabele imaju created_at i updated_at. U tabelama na narednim stranama timestamps podrazumevamo i ne ponavljamo.

UNIQUE označava jedinstvenost. NULL znači da podatak nije primenljiv ili nije poznat; nije isto što i nula. Podrazumevane vrednosti navedene su uz kolone. Nazivi u bazi koriste snake_case.

### Pravila uklanjanja podataka

| Veza | Predlog pri brisanju roditelja |
| --- | --- |
| categories / brands → products | RESTRICT: prvo premestiti proizvode. |
| products → product_images | CASCADE za redove slika; fajlove briše aplikacija. |
| products → order_items | SET NULL za product_id; istorija stavke ostaje. |
| users → user_addresses | CASCADE za sačuvane adrese. |
| users → orders | SET NULL za user_id; istorija porudžbine ostaje. |
| orders → order_items | RESTRICT za fizičko brisanje porudžbine. |

Proizvod povlačimo iz prodaje sa is_active = false. Porudžbine se otkazuju promenom statusa, ne brisanjem. Fizičko brisanje i politika čuvanja ličnih podataka zahtevaju zaseban dogovor.

### Bez dupliranja izvedenih podataka

Broj proizvoda kategorije dobijamo preko veze, ne čuvamo productCount. Procenat popusta izvodimo iz price_cents i old_price_cents. Dostupnost izvodimo iz is_active i stock_quantity. Ukupni iznos porudžbine namerno čuvamo kao istorijski obračun.

## Kategorije i brendovi

### categories

| Kolona / tip | Pravilo i namena |
| --- | --- |
| id · BIGINT UNSIGNED | Primarni ključ. |
| name · VARCHAR(100) | Obavezno; npr. Laptops. |
| slug · VARCHAR(150) | Obavezno, UNIQUE; npr. laptops. |
| icon · VARCHAR(50) | Obavezno; npr. laptop, monitor. |
| color · VARCHAR(30) | Obavezno; kontrolisana oznaka poput c-blue. |
| sort_order · SMALLINT UNSIGNED | DEFAULT 0; redosled u navigaciji. |

Validirati icon i color prema dozvoljenim vrednostima, a ne primati proizvoljan HTML ili CSS. Jedna kategorija može imati više proizvoda. Posebna pivot tabela nije potrebna.

### brands

| Kolona / tip | Pravilo i namena |
| --- | --- |
| id · BIGINT UNSIGNED | Primarni ključ. |
| name · VARCHAR(100) | Obavezno, UNIQUE. |
| slug · VARCHAR(150) | Obavezno, UNIQUE; za adresu stranice brenda. |
| logo_path · VARCHAR(255) | NULL dok logo nije dodat; putanja u storage-u. |
| description · TEXT | NULL; tekst za stranicu brenda. |

Brend dobija sopstvenu administraciju i može imati stranicu koja prikazuje povezane proizvode. U products se čuva brand_id, a ne slobodno unet naziv brenda.

### Primer

Kategorija: Monitors / monitors / monitor / c-indigo. Brend: UltraView / ultraview. Proizvod UltraView 27 povezuje ta dva zapisa pomoću category_id i brand_id.

Promena naziva brenda ne menja istorijski naziv proizvoda u već formiranoj porudžbini. Promena sluga menja javnu adresu; posle objavljivanja izbegavati nepotrebne promene ili uvesti preusmeravanja.

## Products: osnovni podaci i zalihe

### products — zajedničke kolone

| Kolona / tip | Pravilo i namena |
| --- | --- |
| id · BIGINT UNSIGNED | Primarni ključ. |
| category_id · BIGINT UNSIGNED | Obavezan FK ka categories.id. |
| brand_id · BIGINT UNSIGNED | Obavezan FK ka brands.id. |
| sku · VARCHAR(64) | Obavezno, UNIQUE; interna oznaka artikla. |
| name · VARCHAR(255) | Obavezan naziv. |
| slug · VARCHAR(255) | Obavezno, UNIQUE; adresa proizvoda. |
| description · TEXT | Obavezan opis za objavljivanje. |
| price_cents · BIGINT UNSIGNED | Obavezno; trenutna cena u EUR centima. |
| old_price_cents · BIGINT UNSIGNED | NULL ili veća vrednost od price_cents. |
| stock_quantity · INT UNSIGNED | DEFAULT 0; količina raspoloživa za prodaju. |
| is_active · BOOLEAN | DEFAULT false; objava tek nakon validacije. |
| warranty_months · SMALLINT UNSIGNED | NULL kada nije navedeno; trajanje garancije. |
| specifications · JSON | NULL; dodatne opisne specifikacije. |

Numeričke kolone za filtere na sledećoj strani deo su ISTE tabele products. Ne uvodimo tabelu za svaki tip uređaja.

### Pravila za administraciju

SKU i slug proveriti i validacijom i UNIQUE ograničenjem u bazi. Cena ne sme biti negativna; za prodajne artikle zahtevati pozitivnu cenu. stock_quantity = 0 znači nema dostupne zalihe, ali ne mora da sakrije stranicu proizvoda.

JSON služi npr. za opis priključaka ili sadržaja pakovanja. Ne ponavljati RAM, dijagonalu, brend ili cenu u JSON-u. Jedan podatak ima jedno glavno mesto čuvanja.

Aplikacija prikazuje novac formatirano. Unos „429,00“ normalizovati i pretvoriti u 42900 bez računanja preko binarnog float-a. U Laravel modelu postaviti boolean/integer/array cast-ove za odgovarajuća polja.

## Specifikacije, filteri i indeksi

### Dodatne kolone u products

| Kolona / tip | Jedinica i primer |
| --- | --- |
| ram_gb · SMALLINT UNSIGNED NULL | GB; 16. Za uređaje bez RAM specifikacije: NULL. |
| storage_gb · INT UNSIGNED NULL | GB; 512 ili 1024. Dosledno definisati konverziju TB. |
| screen_size_inches · DECIMAL(5,2) NULL | Inči; 14.00, 27.00, 55.00. |
| refresh_rate_hz · SMALLINT UNSIGNED NULL | Hz; 60, 144, 165. |
| resolution_width · SMALLINT UNSIGNED NULL | Pikseli; 1920 ili 3840. |
| resolution_height · SMALLINT UNSIGNED NULL | Pikseli; 1080 ili 2160. |

RAM i dijagonala se ne čuvaju kao tekst „16 GB“ ili „27 inča“. Jedinicu dodaje prikaz. Za pozitivne specifikacije koristiti validaciju i CHECK: vrednost je NULL ili > 0. Rezolucija mora imati obe dimenzije ili nijednu.

### Pretraga i filtriranje

```text
WHERE category_id = :category
  AND is_active = 1
  AND ram_gb = 16
  AND screen_size_inches BETWEEN 14 AND 16
  AND price_cents BETWEEN 50000 AND 150000
```

Backend dodaje samo prosleđene filtere. Vrednosti vezivati kao parametre; ne spajati korisnički tekst u SQL. U URL-u čuvati filtere i page; pri promeni filtera vratiti prvu stranicu.

### Početni indeksi — potvrditi stvarnim upitima

| Indeks na products | Namena |
| --- | --- |
| UNIQUE(slug), UNIQUE(sku) | Pronalaženje proizvoda i jedinstvenost. |
| (category_id, is_active, price_cents) | Kategorija, objava i raspon cena. |
| (category_id, is_active, ram_gb) | Filter po RAM-u u kategoriji. |
| (category_id, is_active, screen_size_inches) | Raspon dijagonale u kategoriji. |
| brand_id | Veza i filtriranje po brendu. |

Ne kreirati sve kombinacije indeksa. Više filtera u istom upitu ne znači da će svi indeksi biti iskorišćeni. Izmeriti preko EXPLAIN i prilagoditi realnim upitima. Indeksi za storage/refresh dodaju se kada ti filteri budu korišćeni.

Za mali katalog naziv/brend može koristiti LIKE. B-tree indeks ne rešava dobro pretragu sa vodećim %. FULLTEXT uvoditi tek uz dogovorenu semantiku pretrage i merenje.

## Galerija i korisničke adrese

### product_images — 2 do 4 slike

| Kolona / tip | Pravilo i namena |
| --- | --- |
| id · BIGINT UNSIGNED | Primarni ključ. |
| product_id · BIGINT UNSIGNED | Obavezan FK ka products.id. |
| path · VARCHAR(255) | Obavezna relativna putanja fajla u storage-u. |
| alt_text · VARCHAR(255) | Opis slike za pristupačnost. |
| sort_order · TINYINT UNSIGNED | Pozicija 1–4; CHECK između 1 i 4. |

Dodati UNIQUE(product_id, sort_order). Prva slika po sort_order predstavlja glavnu sliku. Nije potreban image_path u products, niti is_primary koji bi mogao biti u neskladu sa redosledom.

Za objavljen proizvod aplikacija zahteva 2–4 slike; nacrt može imati 0–4. CHECK ne može sam da obezbedi minimalni broj povezanih redova. Pri dodavanju, brisanju i objavi koristiti transakciju i zaključati proizvod da paralelni zahtevi ne naruše pravilo.

Validirati tip, veličinu i dimenzije slike. U bazi su putanje, ne binarni sadržaj. Uklanjanje redova ne briše storage fajlove automatski. Fajlove čistiti nakon uspešne transakcije i obraditi neuspešna dodavanja.

### user_addresses — sačuvane adrese

| Kolona / tip | Pravilo i namena |
| --- | --- |
| id / user_id · BIGINT UNSIGNED | PK / obavezan FK ka users.id. |
| recipient_name · VARCHAR(150) | Primalac. |
| phone · VARCHAR(30) | Tekst; čuva + i početne nule. |
| address_line1 · VARCHAR(255) | Ulica i broj. |
| address_line2 · VARCHAR(255) NULL | Stan, sprat ili dodatne informacije. |
| city · VARCHAR(100) | Grad. |
| postal_code · VARCHAR(20) | Tekst, ne broj. |
| country_code · CHAR(2) | Oznaka države, npr. RS. |

Adresa pripada korisniku. Server proverava vlasništvo pre korišćenja u checkout-u. Pri kupovini se kopira u orders; kasnija izmena ili brisanje sačuvane adrese ne menja porudžbinu.

users zadržava postojeće auth kolone i is_admin BOOLEAN DEFAULT false. Ne prepravljati Fortify tabele za ovu strukturu; is_admin nije polje javne registracije.

## Porudžbine i istorijske stavke

### orders

| Kolona / tip | Pravilo i namena |
| --- | --- |
| id · BIGINT UNSIGNED | Primarni ključ. |
| number · VARCHAR(40) | Obavezno, UNIQUE; javna oznaka porudžbine. |
| user_id · BIGINT UNSIGNED NULL | FK ka users; novi checkout zahteva prijavu. |
| status · VARCHAR(30) | DEFAULT pending; dozvoljene vrednosti kroz enum u PHP-u. |
| payment_method · VARCHAR(30) | Npr. cash_on_delivery; stvarni izbor dogovoriti. |
| payment_status · VARCHAR(30) | DEFAULT unpaid; nezavisno od statusa isporuke. |
| customer_name / customer_email | VARCHAR(150) / VARCHAR(255); istorijska kopija. |
| phone · VARCHAR(30) | Kontakt za ovu kupovinu. |
| shipping_address_line1 / line2 | VARCHAR(255); line2 je NULL. |
| shipping_city / postal_code | VARCHAR(100) / VARCHAR(20). |
| shipping_country_code · CHAR(2) | Obavezna oznaka države. |
| subtotal_cents · BIGINT UNSIGNED | Zbir stavki. |
| shipping_cents · BIGINT UNSIGNED | DEFAULT 0; trošak dostave. |
| total_cents · BIGINT UNSIGNED | subtotal_cents + shipping_cents. |
| currency · CHAR(3) | EUR; čuva valutu istorijske porudžbine. |
| placed_at · TIMESTAMP | Vreme potvrde porudžbine. |

### order_items

| Kolona / tip | Pravilo i namena |
| --- | --- |
| id / order_id · BIGINT UNSIGNED | PK / obavezan FK ka orders.id. |
| product_id · BIGINT UNSIGNED NULL | FK; može postati NULL ako proizvod bude obrisan. |
| product_name · VARCHAR(255) | Naziv u trenutku kupovine. |
| product_sku · VARCHAR(64) | SKU u trenutku kupovine. |
| unit_price_cents · BIGINT UNSIGNED | Prodajna cena u trenutku kupovine. |
| quantity · INT UNSIGNED | CHECK > 0. |

Iznos stavke = unit_price_cents × quantity. Cene, količine i ukupne iznose obračunava server. Predlog koristi krajnje prodajne cene; poseban poreski obračun i online platne transakcije zahtevaju dodatni dizajn.

## Checkout, prelazak na bazu i provere

### Kreiranje porudžbine u jednoj transakciji

1. Proveri prijavu, korpu, vlasništvo adrese i validnost količina.
2. Zaključaj proizvode u stabilnom redosledu (npr. rastući id). Ponovo proveri is_active, cenu i dovoljnu zalihu.
3. Formiraj istorijske stavke i adresu, izračunaj subtotal i dostavu.
4. Sačuvaj orders i order_items, pa umanji stock_quantity.
5. Potvrdi transakciju i tek tada isprazni korpu / pošalji obaveštenje.

Otkazivanje porudžbine i vraćanje zalihe takođe mora biti transakcija sa zaključavanjem porudžbine. Provera prethodnog statusa sprečava dvostruko vraćanje zalihe. Ne menjati status samo iz proizvoljnog select unosa.

### Statusi i pristup

Predlog statusa: pending → processing → shipped → completed; cancelled samo iz dozvoljenih prethodnih stanja. Payment status: unpaid, paid, refunded. Onlajn potvrda plaćanja ne sme zavisiti samo od povratka browser-a na sajt.

Indeksi orders: UNIQUE(number), (user_id, placed_at), (status, placed_at). order_items: indeksi stranih ključeva. Kupac pristupa samo svojim porudžbinama; admin preko auth + admin provere.

### Redosled implementacije

1. Napravi migracije: categories, brands, products, product_images, user_addresses, orders, order_items.
2. Napravi modele i veze; zatim seedere iz privremenih kataloga.
3. Zameni categorySlug → category_id, brand → brand_id i oldPrice → old_price_cents. Trenutne cene proizvoda su u evrima: pri uvozu ih pretvori u cente tačno jednom. DemoOrderCatalog već koristi cente.
4. Dopuni svaki proizvod sa 2–4 stvarne slike i pouzdanim specifikacijama. Nedostajuće vrednosti ne izmišljati.
5. Poveži admin validaciju/čuvanje, zatim javni katalog, filtere i checkout.

### Provere koje treba napisati

Jedinstven slug/SKU; brisanje povezane kategorije; 2–4 slike pri objavi; ispravne jedinice filtera; old_price > price; nedovoljna zaliha i paralelne kupovine; istorijske cene/adrese ostaju iste; otkazivanje vraća zalihu samo jednom; tuđa porudžbina nije dostupna kupcu.

### Napomena i izvori

Ovo je predlog šeme, ne izvršena migracija. MySQL dokumentacija potvrđuje tačnost DECIMAL tipa i pravila stranih ključeva; izbor tabela, kolona i poslovnih pravila je projektni predlog za Voltio.

MySQL — DECIMAL:
https://dev.mysql.com/doc/refman/8.4/en/fixed-point-types.html
MySQL — FOREIGN KEY constraints:
https://dev.mysql.com/doc/refman/8.4/en/create-table-foreign-keys.html
