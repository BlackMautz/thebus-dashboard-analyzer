# TheBus Dashboard Analyzer 🚌

## 🖼️ **Screenshots - Echte Heinsberg-Daten**

### 🔴 **Live Status Monitor**
![Live Status](https://prnt.sc/sZ1bZy-k0wsS)
*Echtzeit-Überwachung aller aktiven Busse mit Pünktlichkeits-Tracking*

### ⚠️ **Unzugeordnete Ressourcen**  
![Unzugeordnet](https://prnt.sc/dOplAhs2J0TH)
*Automatische Erkennung von Touren ohne Fahrzeugzuordnung*

### 🚌 **Tour-Übersicht mit Details**
![Tour Übersicht](https://prnt.sc/hazELW8jHSQh)
*Detaillierte Analyse aller Touren mit Effizienz-Bewertung*

### � **Haltestellen-Management**
![Haltestellen](https://prnt.sc/riZC2luAtmp9)
*Umfassende Haltestellen-Analyse mit Taktung und Auslastung*

### ⏰ **Fahrplan-Validierung**
![Touren Abfahrtzeiten](https://prnt.sc/S6Z9CpM0Dfp_)
*Automatische Erkennung fehlender Abfahrtzeiten*

### 🚐 **Flotten-Management**
![Flotten Management](https://prnt.sc/xbDVxc-wEB8c)
*Komplette Fahrzeugflotten-Übersicht mit Status-Tracking*

## ✨ **Features im Überblick**
- 🚌 **Real-Time Vehicle Efficiency Analysis**
- 📊 **Interactive Tour Cards mit Click-to-Expand**
- ⏱️ **Echte Standzeiten** (keine Schätzungen!)
- 🎯 **Color-coded Efficiency Ratings**
- 📱 **Responsive Design**
- 🔧 **Easy Setup** (nur config.php anpassen)

---

Ein erweitetes Dashboard zur Analyse von TheBus-Daten mit konfigurierbaren Einstellungen.

## 🚀 **Quick Setup für andere User**

### **1. Dateien kopieren**
- `dashboard_analyzer.php` - Haupt-Dashboard
- `config.php` - Konfigurationsdatei

### **2. Deine Ordnerstruktur erstellen**
```
DeinProjekt/
├── dashboard_analyzer.php
├── config.php
├── DeinOperatingPlan/        ← Dein Ordner mit Linien/Routen/Fahrplänen
│   ├── Lines/
│   ├── Routes/
│   ├── Timetables/
│   └── Tours/
└── DeineFahrzeugflotte/      ← Dein Fahrzeugflotten-Ordner
    └── config.vehiclefleet
```

### **3. Config anpassen (WICHTIG!)**
Öffne `config.php` und ändere nur diese 2 Zeilen:

```php
'paths' => [
    'operating_plan' => 'DeinOperatingPlan',    // ← HIER deinen Ordnernamen
    'vehicle_fleet' => 'DeineFahrzeugflotte',   // ← HIER deinen Ordnernamen
],
```

### **4. Server starten**

#### **Windows (PowerShell/CMD):**
```bash
# In den Projektordner wechseln
cd C:\Pfad\zu\deinem\Projekt

# PHP Server starten
php -S localhost:8080
```

#### **Windows (Explorer):**
1. Öffne den Projektordner im Explorer
2. Klicke in die Adressleiste und tippe `cmd`
3. Drücke Enter (öffnet CMD in diesem Ordner)
4. Tippe: `php -S localhost:8080`

#### **Mac/Linux (Terminal):**
```bash
# In den Projektordner wechseln  
cd /pfad/zu/deinem/projekt

# PHP Server starten
php -S localhost:8080
```

#### **Alternative Ports (falls 8080 belegt):**
```bash
php -S localhost:8000   # Port 8000
php -S localhost:3000   # Port 3000
php -S localhost:9000   # Port 9000
```

#### **Server stoppen:**
- **Windows**: `Ctrl + C` im Terminal
- **Mac/Linux**: `Ctrl + C` im Terminal
- **Oder**: Terminal-Fenster schließen

#### **Troubleshooting:**
- **"php ist nicht erkannt"**: PHP nicht installiert → [PHP Download](https://www.php.net/downloads)
- **"Port bereits verwendet"**: Anderen Port verwenden (siehe oben)
- **Server läuft nicht**: Prüfe ob du im richtigen Ordner bist (`ls` oder `dir`)

### **5. Browser öffnen**
```
http://localhost:8080
```

**(Wichtig: Der Server muss laufen, sonst geht die Seite nicht!)**

---

## 🆘 **Für absolute Anfänger**

### **PHP installieren (falls nicht vorhanden):**

#### **Windows:**
1. Gehe zu [php.net/downloads](https://www.php.net/downloads.php)
2. Lade "Thread Safe" Version herunter
3. Entpacke nach `C:\php\`
4. Füge `C:\php\` zu deiner PATH-Variable hinzu:
   - Windows-Taste + R → `sysdm.cpl`
   - Erweitert → Umgebungsvariablen
   - PATH bearbeiten → Neu → `C:\php\`

#### **Mac (mit Homebrew):**
```bash
brew install php
```

#### **Ubuntu/Debian:**
```bash
sudo apt update
sudo apt install php
```

### **Test ob PHP funktioniert:**
```bash
php --version
```
*Sollte PHP Version anzeigen*

### **Komplett-Anleitung Schritt für Schritt:**

1. **Ordner kopieren**: Gesamten `MainServerTest` Ordner kopieren
2. **Terminal öffnen**: 
   - Windows: `Shift + Rechtsklick` im Ordner → "PowerShell hier öffnen"
   - Mac: `Terminal` öffnen und `cd` zum Ordner
3. **Config anpassen**: `config.php` öffnen und Ordnernamen ändern
4. **Server starten**: `php -S localhost:8080` eingeben
5. **Browser öffnen**: `http://localhost:8080` aufrufen
6. **Fertig!** 🎉

## ⚙️ **Optionale Anpassungen**

### **Rush Hour Zeiten ändern:**
```php
'rush_hours' => [
    'morning' => ['start' => 7, 'end' => 9],    // Deine Morgenstunden
    'evening' => ['start' => 16, 'end' => 18],  // Deine Abendstunden
],
```

### **Verkehrsdichte-Schwellenwerte:**
```php
'traffic_density' => [
    'high' => 50,    // Ab wieviel Fahrten = "Hoch"
    'medium' => 20,  // Ab wieviel Fahrten = "Medium"
],
```

### **Zeitkategorien anpassen:**
```php
'time_slots' => [
    'Nacht (00-05)',
    'Früh (06-09)', 
    // ... deine eigenen Kategorien
],
```

### **Farben ändern:**
```php
'ui' => [
    'primary_color' => '#007bff',      // Hauptfarbe
    'rush_hour_color' => '#ff6b35',   // Rush Hour Farbe
    // ... weitere Farben
],
```

## 📊 **Features**

### **Tabs:**
- **Übersicht**: Statistiken und Zusammenfassung
- **Live**: Echtzeit-Bus-Tracking (simuliert)
- **Unassigned**: Touren ohne Fahrzeuge
- **Validierung**: Gesundheitscheck der Daten
- **Touren**: Detaillierte Tour-Analyse
- **Effizienz**: 🆕 **Fahrzeug-Effizienz Analyse mit echten Standzeiten**
- **Haltestellen**: Erweiterte Haltestellen-Analyse mit Entwickler-Infos
- **Fahrpläne**: Timetable-Übersicht
- **Analytics**: Weitere Analysen
- **Fahrzeuge**: Fahrzeugflotten-Details
- **Export**: Daten-Export

### **Erweiterte Haltestellen-Analyse:**
- 🔧 **Entwickler-Infos** (klickbar ausklappbar)
- **Route-Details** mit Fahrzeiten
- **24h Zeitverteilung** mit grafischen Balken
- **Taktung & Lücken-Analyse** 
- **Optimierungsvorschläge**
- **Raw JSON Data** für APIs
- **Vollständige Abfahrtslisten** (keine Begrenzung mehr)

### **🆕 Fahrzeug-Effizienz Analyse:**
- **Echte Standzeiten-Berechnung** aus realStartTimes (keine Schätzungen!)
- **Tour-Card Design** für bessere Übersicht
- **Effizienz-Bewertung** mit Farbkodierung (Grün/Gelb/Rot)
- **Detaillierte Fahrzeug-Infos:**
  - **Kennzeichen + Fahrzeug-ID** für eindeutige Identifikation
  - **Arbeitszeit vs. Aktive Zeit** vs. **Standzeit**
  - **Echte Standzeit-Details** mit Orten und Uhrzeiten
  - **Tour-Auflistung** mit Namen und Linien
- **Klickbare Cards** zum Aus-/Einklappen von Details
- **Gesamt-Optimierungsempfehlungen** für die Flotte

### **Validation-System:**
- Health Scoring der Daten
- Fahrzeug-Mehrfachzuweisungen
- Unassigned Tours Detection
- Detaillierte Fehleranalyse

## 🛠️ **Für Entwickler**

### **Code-Struktur:**
- `config.php` - Alle konfigurierbaren Werte
- `DashboardAnalyzer` Klasse - Haupt-Logik
- Modulare Tab-Funktionen
- JSON-Datenverarbeitung mit UTF-8 BOM-Handling

### **Datenquellen:**
- TheBus `.line`, `.lineRoute`, `.timetable` Dateien
- Vehicle Fleet `config.vehiclefleet`
- Automatische Pfad-Erkennung

### **Erweiterbar:**
- Neue Tabs einfach hinzufügbar
- Konfigurierbare Schwellenwerte
- Mehrsprachigkeit vorbereitet
- CSS-Variablen für Theming

## 📝 **Support**

Bei Problemen:
1. Prüfe die Ordnerpfade in `config.php`
2. Stelle sicher, dass die Datenstruktur TheBus-Standard entspricht
3. Schaue in die Browser-Konsole für JavaScript-Fehler
4. Prüfe PHP-Errors im Terminal

---

**Erstellt für TheBus Community** 🚌💙