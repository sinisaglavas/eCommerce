# Git i PhpStorm — timski rad

## Osnove koje uvek proveravaš

### Git i PhpStorm — timski rad

Praktično uputstvo za štampu • Voltio • 12. oktobar 2026.

### Najvažnije pravilo

MERGE unosi izabranu granu U TRENUTNU granu. Pre klika pročitaj ceo naziv operacije: Merge IZVOR into ODREDIŠTE.

Primer: da katalog stigne u master, prvo pređi na master, pa izaberi koleginu granu i Merge into master. Da katalog stigne u tvoj admin panel, pređi na admin-panel i spoji master u nju.

### Šta koja operacija radi

Commit — čuva izabrane izmene lokalno u istoriji grane. Push — šalje commitove na server. Ne šalje necommitovane fajlove.

Fetch — preuzima informacije i commitove sa servera; ne spaja ih u tvoju trenutnu granu. Pull — preuzima i uključuje udaljene promene u lokalnu granu.

Checkout / Switch — prelazi na drugu granu. Merge — spaja istoriju i izmene druge grane u trenutnu. Pull Request (PR) — predlog da se grana pregleda i spoji na serveru.

master je lokalna grana. origin/master je lokalni zapis poslednjeg preuzetog stanja udaljene grane. Zato prvo radi Fetch. Neki projekti koriste naziv main umesto master.

### Pre promene grane ili merge-a

1. Proveri naziv trenutne grane u PhpStorm Git meniju.
2. Pregledaj Commit / Local Changes prozor.
3. Commituj smislen završen rad ili privremeno sačuvaj izmene u stash.
4. Pokreni Git > Fetch.
5. Proveri izvornu i ciljnu granu.

```bash
git status
git branch --show-current
git fetch origin
```

Komande pokreći iz korena repozitorijuma, npr. ~/eCommerce, u PhpStorm terminalu na računaru. Za Git nije potrebno ulaziti u PHP kontejner.

## Nova funkcionalnost i slanje svog rada

### 1. Nova grana od ažuriranog master-a

Počni sa sačuvanim radom. U meniju grana izaberi Local > master > Checkout. Zatim Git > Pull: origin, master. Za običnu sinhronizaciju koristi fast-forward only ako je ponuđen; ako nije, terminalska komanda ispod daje preciznu kontrolu.

Iz master-a izaberi New Branch, unesi feature/naziv-zadatka i uključi Checkout branch. Na primer: feature/admin-panel.

```bash
git switch master
git pull --ff-only origin master
git switch -c feature/naziv-zadatka
```

Ako grana već postoji, koristi Checkout / git switch, bez -c. Ako pull --ff-only prijavi da ne može da nastavi, stani i pregledaj Git Log: lokalni master i server imaju različite nove commitove.

### 2. Radi, pregledaj i commituj

Otvori Commit prozor, pregledaj diff i označi samo fajlove za taj zadatak. Napiši jasnu poruku, npr. feat: protect admin routes. Izaberi Commit. Ne uključuj .env, lozinke ili nepovezane izmene.

```bash
git status
git add putanja/do/fajla
git commit -m "feat: protect admin routes"
```

### 3. Push svoje grane

Git > Push (Ctrl + Shift + K u standardnom Windows/Linux keymap-u). Proveri da je smer feature/naziv-zadatka → origin/feature/naziv-zadatka. Pri prvom push-u potvrdi kreiranje udaljene grane.

```bash
git push -u origin feature/naziv-zadatka
```

Posle prvog povezivanja grane obično je dovoljan git push. Commit sam po sebi ne objavljuje rad kolegama.

### 4. Predloži spajanje kroz Pull Request

Na GitHub-u/GitLab-u otvori PR/MR: izvor je tvoja feature grana, cilj je master. Opiši promenu i provere. Kolega pregleda izmene, prođu provere i zatim se PR spaja prema dogovoru tima.

Za zajednički projekat ovo je preporučen tok. Ako je master zaštićen, direktan push može biti zabranjen; tada koristi PR. Ne radi i lokalni merge i zaseban PR merge za isti posao bez potrebe.

## Kolegin rad: kako stiže u master

### Opcija A — kolegin PR je već spojen

Ne spajaj ponovo njegovu feature granu. Sačuvaj svoj rad, pređi na master i povuci stanje sa servera. Zatim nastavi na sledeću stranu ovog uputstva.

```bash
git switch master
git pull --ff-only origin master
```

### Opcija B — vi spajate lokalno

Koristi kada je tim dogovorio direktno spajanje i push na master. Primer kolegine grane: feature/ecommerce-v1. Naziv prepiši tačno iz Remote liste.

1. Sačuvaj svoj rad commitom ili stash-om.
2. Git > Fetch.
3. Local > master > Checkout.
4. Git > Pull: origin/master; prvo ažuriraj master.
5. Remote > origin > feature/ecommerce-v1.
6. Izaberi Merge origin/feature/ecommerce-v1 into master.
7. Reši konflikte ako postoje i proveri aplikaciju.
8. Git > Push: proveri master → origin/master.

```bash
git fetch origin
git switch master
git pull --ff-only origin master
git merge origin/feature/ecommerce-v1
# Proveri aplikaciju pre sledece komande
git push origin master
```

### Šta da proveriš pre Push-a

U Push prozoru pregledaj sve commitove koji odlaze. Oznaka ↑10 uz master znači da lokalno imaš 10 commitova više od praćene udaljene grane prema poznatom stanju. To nisu nužno samo tvoji commitovi.

Merge može samo da pomeri granu unapred (fast-forward) ili da napravi merge commit. Obe situacije su normalne. Poruka Already up to date znači da su commitovi izvora već uključeni.

### Ako Push bude odbijen

Kolega je možda u međuvremenu promenio master. Uradi Fetch i pregledaj Log. Sa čistim radnim folderom uključi novo stanje origin/master kroz merge, reši konflikte, proveri aplikaciju i ponovi push. Ne koristi Force Push za zajednički master.

```bash
git fetch origin
git merge origin/master
# Provera, pa git push origin master
```

## Nastavak rada na tvojoj admin grani

### Ažurirani master → feature/admin-panel

Kada je lokalni master ažuriran, vrati se na svoju granu. Spajanje master-a u nju donosi kolegin katalog, a zadržava tvoje commitove admin panela.

U PhpStorm-u:
1. Local > feature/admin-panel > Checkout.
2. Local > master > Merge master into feature/admin-panel.
3. Reši konflikte i proveri aplikaciju.
4. Git > Push: feature/admin-panel → origin/feature/admin-panel.

```bash
git switch feature/admin-panel
git merge master
# Proveri aplikaciju
git push -u origin feature/admin-panel
```

Ovim se admin panel NE spaja u master. Kada admin panel bude spreman, napravi njegov PR prema master-u.

### Ako rad još nije za commit: stash

Stash privremeno sklanja necommitovan rad. Nove fajlove uključi opcijom -u. To što fajl nestane iz foldera posle stash-a je očekivano; proveri listu sačuvanih izmena.

```bash
git stash push -u -m "Admin panel rad u toku"
git stash list
```

Posle ažuriranja master-a i spajanja u svoju granu vrati odgovarajući stash. Ako ima više stavki, izaberi pravu prema poruci; stash@{0} je najnoviji.

```bash
git switch feature/admin-panel
git stash show --stat --include-untracked "stash@{0}"
git stash pop "stash@{0}"
```

U PhpStorm-u potraži Git > Uncommitted Changes > Stash/Unstash Changes; raspored menija zavisi od verzije. Shelf je drugi mehanizam i nije isto što i Git stash. Za nove fajlove najjasnija je komanda sa -u.

Ako pop napravi konflikt, stash ostaje sačuvan. Reši konflikt i ne pokreći pop ponovo naslepo. Stash je lokalni; ne šalje se običnim push-om.

### Važno za migracije i nove fajlove

Necommitovan fajl nije trajno vezan za granu i može ostati vidljiv posle Checkout-a. Za pouzdano razdvajanje commituj ga na odgovarajućoj grani ili koristi stash. Merge i push prenose commitove, a ne necommitovane fajlove.

## Konflikti i kontrolna lista

### Kada Git prijavi konflikt

Stani sa ostalim koracima. Otvori PhpStorm Resolve Conflicts / Merge. Pročitaj oznake izvora i cilja u prozoru, pregledaj razlike i sastavi ispravan rezultat. Ne prihvataj automatski sve sa jedne strane: često su potrebne izmene oba autora.

Sačuvaj rezultat, označi konflikt kao rešen i završi merge commit ako je potreban. Proveri da nema oznaka <<<<<<<, ======= i >>>>>>> u fajlovima. Pokreni relevantne provere aplikacije.

```bash
git status
git add putanja/do/resenog-fajla
git merge --continue
```

Ako želiš da odustaneš od merge-a koji još traje, koristi Abort Merge u IDE-u ili komandu ispod. Najpouzdanije je kada je radni folder bio čist pre merge-a. Za već završen/pushovan merge ovo nije postupak poništavanja.

```bash
git merge --abort
```

### Posle spajanja Laravel projekta

Pregledaj šta je promenjeno i uputstvo kolege. Ako je promenjen composer.lock, pokreni composer install u workspace kontejneru. Ako je promenjen package-lock.json, koristi npm ci. Ako postoje nove migracije, pregledaj ih i pokreni php artisan migrate na lokalnoj bazi. Pokreni build i testove primerene promeni.

Git merge ne izvršava migracije i ne menja automatski strukturu baze. Ne koristi migrate:fresh za uobičajeno preuzimanje rada — briše tabele.

### Brza provera pre svakog spajanja

[ ] Znam na kojoj grani sam.
[ ] Moj rad je commitovan ili sačuvan u stash-u.
[ ] Fetch je završen i znam tačan naziv izvora.
[ ] Ciljna grana je prava: Merge IZVOR into CILJ.
[ ] Konflikti su rešeni i aplikacija je proverena.
[ ] U Push prozoru provereni su grana i commitovi.

### Podsetnik za tvoj projekat

Kolegin katalog: origin/feature/ecommerce-v1 → master.
Preuzimanje kataloga u admin: master → feature/admin-panel.
Završen admin panel: feature/admin-panel → master, najbolje kroz PR.

### Zvanična dokumentacija

PhpStorm: Merge, rebase, or cherry-pick to apply changes
https://www.jetbrains.com/help/phpstorm/apply-changes-from-one-branch-to-another.html
PhpStorm: Sync with a remote Git repository
https://www.jetbrains.com/help/phpstorm/sync-with-a-remote-repository.html
Git stash: https://git-scm.com/docs/git-stash
