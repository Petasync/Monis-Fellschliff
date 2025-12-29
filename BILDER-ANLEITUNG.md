# 📸 Bilder-Anleitung für Moni's Fellschliff Website

## Überblick

Die Webseite wurde komplett überarbeitet mit Mobile-First Design und SEO-Optimierung. Alle Bildplatzhalter sind vorbereitet und müssen nur noch mit den richtigen Bildern befüllt werden.

## 📁 Ordnerstruktur

Die folgenden Ordner wurden erstellt:

```
assets/
└── images/
    ├── hero/          # Hero-Bereich Bilder
    ├── salon/         # Salon Impressionen (Video)
    ├── about/         # Über mich Bilder
    ├── gallery/       # Galerie Bilder
    ├── signs/         # Salon Schilder
    └── logo/          # Logo Dateien (optional)
```

## 🖼️ Benötigte Bilder

### 1. Hero-Bereich (Startseite)
**Pfad:** `assets/images/hero/dogs-on-bench.jpg`
- **Beschreibung:** Hunde auf der Bank
- **Empfohlene Größe:** 1920x1080px (16:9)
- **Format:** JPG oder WebP
- **Qualität:** Hoch, nicht unscharf
- **Hinweis:** Wird als Hintergrundbild verwendet mit Overlay

### 2. Salon Impression Video
**Pfad:** `assets/images/salon/salon-video.mp4`
- **Beschreibung:** Video vom Salon
- **Empfohlene Format:** MP4 (H.264)
- **Aspect Ratio:** 16:9
- **Maximale Größe:** 50MB
- **Hinweis:** Nach Upload HTML-Kommentar entfernen und Video aktivieren:
  ```html
  <video controls autoplay muted loop>
      <source src="assets/images/salon/salon-video.mp4" type="video/mp4">
  </video>
  ```

### 3. Über Mich Bild
**Pfad:** `assets/images/about/monika-portrait.jpg`
- **Beschreibung:** Porträt von Monika (von WhatsApp)
- **Empfohlene Größe:** 800x800px (quadratisch)
- **Format:** JPG oder WebP
- **Qualität:** Hoch, professionell
- **Hinweis:** Wird automatisch responsive angezeigt

### 4. Galerie Bilder (von Instagram/WhatsApp)
**Pfad:** `assets/images/gallery/`
- **Dateinamen:** `dog-1.jpg`, `dog-2.jpg`, etc.
- **Anzahl:** 12-20 Bilder empfohlen
- **Empfohlene Größe:** 800x800px (quadratisch für Instagram-Look)
- **Format:** JPG oder WebP
- **Qualität:** Hoch, nicht unscharf
- **Hinweis:** Von Instagram und WhatsApp die besten Bilder auswählen
- **HTML-Anpassung:** In `Galerie.html` die Kommentare entfernen:
  ```html
  <div class="gallery-item">
      <img src="assets/images/gallery/dog-1.jpg" alt="Hund nach Pflege">
  </div>
  ```

### 5. Salon Schilder
**Pfad:** `assets/images/signs/`
- **Dateinamen:** `sign-1.jpg`, `sign-2.jpg`
- **Beschreibung:** Bilder von den Schildern neben dem Salon
- **Empfohlene Größe:** 600x800px (3:4 Hochformat)
- **Format:** JPG oder WebP
- **Qualität:** Gut lesbar
- **Hinweis:** Werden neben dem Kontaktformular angezeigt

## ✅ Checkliste für Bilder

### Qualitätskriterien:
- [ ] Bilder sind nicht unscharf
- [ ] Gute Beleuchtung
- [ ] Professionell wirkend
- [ ] Richtige Größe/Format
- [ ] Komprimiert für Web (nicht zu groß)

### Empfohlene Bild-Optimierung:
1. **Größe reduzieren:** Max. 2000px Breite
2. **Komprimieren:** Mit TinyPNG oder ähnlichen Tools
3. **Format:** WebP bevorzugt (bessere Kompression), JPG als Fallback
4. **Dateinamen:** Kleinbuchstaben, keine Leerzeichen, Bindestriche statt Unterstriche

## 🔄 Bilder hochladen und aktivieren

### Schritt 1: Bilder hochladen
Laden Sie die Bilder in die entsprechenden Ordner hoch:
```bash
# Beispiel mit FTP/SFTP oder Datei-Manager
/assets/images/hero/dogs-on-bench.jpg
/assets/images/salon/salon-video.mp4
/assets/images/about/monika-portrait.jpg
/assets/images/gallery/dog-1.jpg
/assets/images/gallery/dog-2.jpg
...
/assets/images/signs/sign-1.jpg
/assets/images/signs/sign-2.jpg
```

### Schritt 2: HTML-Kommentare entfernen
In den HTML-Dateien sind alle Bild-Tags vorbereitet. Entfernen Sie die Kommentare:

**Beispiel index.html - Über Mich Sektion:**
```html
<!-- VORHER: -->
<!-- <img src="assets/images/about/monika-portrait.jpg" alt="..."> -->
<div class="image-placeholder">...</div>

<!-- NACHHER: -->
<img src="assets/images/about/monika-portrait.jpg" alt="...">
<!-- <div class="image-placeholder">...</div> -->
```

**Beispiel Galerie.html - Galerie Bilder:**
```html
<!-- VORHER: -->
<div class="gallery-item">
    <!-- <img src="assets/images/gallery/dog-1.jpg" alt="Hund nach Pflege"> -->
    <div class="gallery-placeholder">🐕 Bild 1...</div>
</div>

<!-- NACHHER: -->
<div class="gallery-item">
    <img src="assets/images/gallery/dog-1.jpg" alt="Hund nach Pflege">
</div>
```

### Schritt 3: Salon Video aktivieren
In `index.html` Zeile ~1061:
```html
<!-- VORHER: -->
<!-- <video controls><source src="assets/images/salon/salon-video.mp4" type="video/mp4"></video> -->
<div class="salon-video-placeholder">📹 Salon Video...</div>

<!-- NACHHER: -->
<video controls autoplay muted loop>
    <source src="assets/images/salon/salon-video.mp4" type="video/mp4">
    Ihr Browser unterstützt das Video-Tag nicht.
</video>
```

## 🌐 Cloudflare Analytics

Cloudflare Web Analytics ist vorbereitet. Token muss ersetzt werden:

**In index.html und Galerie.html:**
```html
<script defer src='https://static.cloudflareinsights.com/beacon.min.js'
        data-cf-beacon='{"token": "YOUR_CLOUDFLARE_TOKEN"}'></script>
```

**So bekommen Sie Ihren Token:**
1. Gehen Sie zu [Cloudflare Dashboard](https://dash.cloudflare.com/)
2. Wählen Sie Ihre Website
3. Gehen Sie zu "Analytics" → "Web Analytics"
4. Kopieren Sie den Token
5. Ersetzen Sie `YOUR_CLOUDFLARE_TOKEN` mit Ihrem echten Token

## 📱 Mobile-First & SEO Optimierung

Die Webseite ist bereits vollständig optimiert für:

### Mobile-First Design:
- ✅ Responsive Layout für alle Bildschirmgrößen
- ✅ Touch-optimierte Navigation
- ✅ Optimierte Schriftgrößen mit `clamp()`
- ✅ Mobile-freundliche Buttons und Links

### SEO Optimierung:
- ✅ Semantisches HTML
- ✅ Meta-Tags (Title, Description, Keywords)
- ✅ Open Graph Tags
- ✅ Canonical URLs
- ✅ Alt-Texte für Bilder (wenn Bilder hochgeladen werden)
- ✅ Strukturierte Überschriften (H1, H2, H3)
- ✅ Schnelle Ladezeiten durch optimiertes CSS

### Neue Features:
- ✅ **Logo mit 2 Tatzen** oben links
- ✅ **Hero-Bereich** mit Hintergrundbild-Support
- ✅ **Salon Video Sektion** vorbereitet
- ✅ **Instagram & Facebook Links** im Footer und Content
- ✅ **Google Maps** bereits integriert
- ✅ **Google Reviews Carousel** bereits funktional
- ✅ **FAQ "Warum zittern Hunde"** hinzugefügt mit Link zur Hauptseite
- ✅ **Schilder-Bilder** neben Kontaktformular
- ✅ **Moderne Galerie** mit Instagram-Grid-Layout
- ✅ **Cloudflare Analytics** vorbereitet

## 🔗 Social Media Links

Die folgenden Links sind bereits eingebaut:
- Instagram: `https://www.instagram.com/monis_fellschliff/`
- Facebook: `https://www.facebook.com/MonisfellschliffDietenhofen`

Falls sich die URLs ändern, können Sie diese in den HTML-Dateien anpassen.

## 📝 Weitere Hinweise

### Bild-Formate:
- **WebP:** Beste Kompression, moderne Browser
- **JPG:** Gute Kompression, alle Browser
- **PNG:** Für Grafiken mit Transparenz (Logo)

### Bild-Komprimierung Tools:
- [TinyPNG](https://tinypng.com/) - Kostenlos, sehr gut
- [Squoosh](https://squoosh.app/) - Google Tool, WebP Support
- [ImageOptim](https://imageoptim.com/) - Mac App

### Testen:
Nach dem Hochladen der Bilder testen Sie:
- [ ] Desktop-Ansicht (Chrome, Firefox, Safari)
- [ ] Mobile-Ansicht (Smartphone)
- [ ] Tablet-Ansicht
- [ ] Ladegeschwindigkeit (Google PageSpeed Insights)

## 🆘 Support

Bei Fragen oder Problemen:
1. Prüfen Sie die Dateipfade (Groß-/Kleinschreibung!)
2. Prüfen Sie die Browser-Konsole (F12) auf Fehler
3. Stellen Sie sicher, dass die Bilder hochgeladen wurden

---

**Viel Erfolg mit der neuen Webseite! 🐾**
