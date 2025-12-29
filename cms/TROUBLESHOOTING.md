# 🔧 Admin Panel Troubleshooting

## ❌ Error 500 - Internal Server Error

### Mögliche Ursachen:

1. **PHP nicht aktiviert**
   - Lösung: Kontaktieren Sie IP Projects und bitten Sie um PHP-Aktivierung
   - Benötigt: PHP 7.4 oder höher

2. **Sessions funktionieren nicht**
   - Lösung: Prüfen Sie ob `/tmp` Schreibrechte hat
   - Oder: Kontaktieren Sie Ihren Hoster

3. **Dateipfade falsch**
   - Lösung: Prüfen Sie ob alle Ordner existieren:
     - `/cms/`
     - `/assets/images/gallery/`

4. **Fehlende PHP-Extensions**
   - Benötigt: `session`, `json`, `fileinfo`
   - Lösung: Hoster kontaktieren

---

## ✅ Manuelle Galerie-Verwaltung (Ohne Admin Panel)

### Schritt 1: Bilder hochladen

Via FTP oder Dateimanager:
- Ordner: `/assets/images/gallery/`
- Benennung: `dog-21.webp`, `dog-22.webp`, etc.
- Format: WebP, JPG oder PNG

### Schritt 2: Galerie.html bearbeiten

**Für Vorher/Nachher-Bilder:**

Suchen Sie im Code nach:
```html
<!-- Vorher/Nachher Gallery -->
```

Fügen Sie hinzu:
```html
<div class="gallery-item animate-in">
    <img src="assets/images/gallery/VORHER.webp" alt="Vorher" loading="lazy">
</div>
<div class="gallery-item animate-in">
    <img src="assets/images/gallery/NACHHER.webp" alt="Nachher" loading="lazy">
</div>
```

**Für normale Hundebilder:**

Suchen Sie nach:
```html
<!-- Normale Hunde-Galerie -->
```

Fügen Sie hinzu:
```html
<div class="gallery-item animate-in">
    <img src="assets/images/gallery/BILDNAME.webp" alt="Hund" loading="lazy">
</div>
```

### Schritt 3: Speichern & Testen

1. Datei speichern
2. Website neu laden
3. Galerie prüfen

---

## 🆘 Wenn nichts funktioniert

**Option 1: Hoster kontaktieren**
- IP Projects anrufen
- PHP-Aktivierung anfragen
- Sessions-Unterstützung prüfen

**Option 2: Webentwickler kontaktieren**
- Petasync.de
- Admin Panel neu einrichten

**Option 3: Alternative CMS**
- WordPress mit Galerie-Plugin
- Oder: Einfach manuell Bilder hinzufügen (siehe oben)

---

## 📝 Backup erstellen!

**Vor jeder Änderung:**
1. `Galerie.html` kopieren
2. Als `Galerie-BACKUP-DATUM.html` speichern
3. Dann erst bearbeiten

So können Sie bei Fehlern zurückgehen!

---

## ✅ Checkliste für Hoster (IP Projects)

Fragen Sie:
- ✅ Ist PHP aktiviert? (mind. PHP 7.4)
- ✅ Sind Sessions aktiviert?
- ✅ Hat `/tmp` Schreibrechte?
- ✅ Ist `file_get_contents()` erlaubt?
- ✅ Ist `move_uploaded_file()` erlaubt?

Wenn alles JA → Admin Panel sollte funktionieren!

---

## 🔐 Sicherheitshinweise

- Passwort in `config.php` ändern!
- Nur über HTTPS zugreifen
- Regelmäßig Backups erstellen
