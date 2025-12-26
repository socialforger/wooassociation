# WooAssociation
Gestione adesione ad associazione, con quota automatica e rinnovo annuale, tramite WooCommerce.

Il plugin è progettato per piattaforme di acquisto collettivo, GAS, enti del terzo settore e comunità che richiedono una quota associativa annuale per partecipare alle attività.

---

## ✨ Funzionalità principali

### 🔐 1. Adesione automatica al primo ordine
- L’utente si registra normalmente (con WooCommerce o con plugin esterni come *Woo Magic Login*).
- Completa il proprio profilo (CF, statuto, privacy).
- Al **primo ordine**, Woo Association aggiunge automaticamente al carrello la **quota associativa**.
- L’adesione viene attivata al completamento dell’ordine.

### 🔄 2. Rinnovo automatico annuale
- L’adesione dura un numero configurabile di giorni (default: 365).
- Alla scadenza, l’utente può continuare a navigare.
- Al **primo ordine successivo alla scadenza**, la quota viene aggiunta automaticamente al carrello.
- Il rinnovo viene registrato al completamento dell’ordine.

### 🧾 3. Quota associativa configurabile
- L’amministratore può selezionare un **prodotto WooCommerce** come quota associativa.
- L’importo può essere modificato direttamente dal prodotto WooCommerce.
- Supporta prodotti semplici, variabili e “name your price”.

### 👤 4. Campi profilo aggiuntivi
Woo Association aggiunge al profilo WooCommerce:
- Codice fiscale  
- Accettazione statuto  
- Accettazione privacy  

Il profilo deve essere completo per procedere al checkout (opzione configurabile).

### 🧩 5. API pubbliche per altri plugin
Woo Association espone una API interna per verificare:
- stato di adesione  
- data di inizio  
- data di scadenza  
- necessità di rinnovo  

Perfetto per integrazioni con piattaforme come *WooSocialMarket*.

---

## 📦 Installazione

1. Copia la cartella `wooassociation` in:
wp-content/plugins/
2. Attiva il plugin da:
Bacheca → Plugin
3. Vai in:
WooCommerce → Associazione
e configura:
- ID prodotto quota associativa  
- durata adesione  
- obbligo profilo completo  

---

## ⚙️ Configurazione

### 1. Seleziona il prodotto quota associativa
Crea un prodotto WooCommerce (es. “Quota associativa annuale”) e inserisci il suo ID nelle impostazioni del plugin.

### 2. Imposta la durata dell’adesione
Default: **365 giorni**  
Puoi modificarla liberamente.

### 3. Richiedi profilo completo
Se attivo:
- l’utente deve compilare CF, statuto e privacy prima di poter acquistare.

---

## 🧠 Come funziona

### Primo ordine
- Se l’utente non è socio e il profilo è completo → la quota viene aggiunta al carrello.

### Rinnovo
- Se l’adesione è scaduta → la quota viene aggiunta al carrello al primo ordine utile.

### Attivazione
- L’adesione viene attivata solo quando l’ordine viene **completato**.

---

## 🧩 API interne

Woo Association espone metodi statici:

```php
WooAssociation\API::is_member( $user_id );
WooAssociation\API::needs_renewal( $user_id );
WooAssociation\API::get_membership_data( $user_id );
WooAssociation\API::set_membership( $user_id, $duration_days );

Perfetto per plugin come:
• 	Woo Social Market
• 	sistemi di gruppi
• 	punti di ritiro
• 	ordini collettivi

🌍 Traduzioni
Il plugin include la cartella:
languages/
con il file:
wooassociation.pot
Puoi tradurre con:
• 	Poedit
• 	Loco Translate
• 	WP‑CLI

🧱 Struttura del plugin
wooassociation/
  wooassociation.php
  includes/
    class-wooassociation-plugin.php
    class-wooassociation-admin.php
    class-wooassociation-profile.php
    class-wooassociation-membership.php
    class-wooassociation-checkout.php
    class-wooassociation-api.php
  languages/
    wooassociation.pot
  README.md

🤝 Contributi
Pull request, issue e suggerimenti sono benvenuti.
Il plugin è progettato per essere estensibile, modulare e integrabile in ecosistemi più ampi.

📄 Licenza
GNU GPL V.2

