# TheBus Dashboard Analyzer 🚌

## 🎯 **Was ist das hier?**

Dieses Dashboard analysiert deine TheBus-Daten und zeigt dir alles Wichtige auf einen Blick:
- **Fahrzeug-Effizienz** mit echten Standzeiten
- **Tour-Analysen** mit detaillierten Einblicken  
- **Haltestellen-Übersicht** mit Entwickler-Tools
- **Validierung** deiner Datenstruktur
- **Live-Tracking** (Demo-Modus)

## ✨ **Hauptfeatures**
- 🚌 **Echte Fahrzeug-Standzeiten** (keine Schätzungen!)
- 📊 **Klickbare Tour-Cards** mit erweiterbaren Details
- 🎯 **Farbkodierte Effizienz-Bewertungen** (Grün/Gelb/Rot)
- 📱 **Responsive Design** für alle Geräte
- 🔧 **Einfache Konfiguration** über config.php
- 🚨 **Automatische Datenvalidierung**

---

## 🚀 **So startest du das Dashboard**

### **Option 1: Alles ist schon fertig (Windows)**
1. **Terminal öffnen**: 
   - Shift + Rechtsklick in diesem Ordner → "PowerShell hier öffnen"
   - Oder: Explorer-Adressleiste anklicken → powershell tippen → Enter

2. **Server starten**:
   ```bash
   php -S localhost:8080
   ```

3. **Browser öffnen**:
   ```
   http://localhost:8080
   ```

4. **Fertig!** 🎉 Das Dashboard läuft jetzt
### **Option 2: Für andere TheBus-Nutzer**

#### **1. Diese Dateien kopieren:**
- dashboard_analyzer.php (das Hauptprogramm)
- config.php (die Einstellungen)

#### **2. Deine Ordnerstruktur:**
`
DeinProjekt/
├── dashboard_analyzer.php
├── config.php
├── DeinOperatingPlan/        ← Dein Ordner mit den Buslinien
│   ├── Lines/
│   ├── Routes/
│   ├── Timetables/
│   └── Tours/
└── DeineFahrzeugflotte/      ← Dein Fahrzeugflotten-Ordner
    └── config.vehiclefleet
`

#### **3. Einstellungen anpassen (WICHTIG!)**
Öffne config.php und ändere nur diese 2 Zeilen:

`php
'paths' => [
    'operating_plan' => 'DeinOperatingPlan',    // ← HIER deinen Ordnernamen eintragen
    'vehicle_fleet' => 'DeineFahrzeugflotte',   // ← HIER deinen Ordnernamen eintragen  
],
`

#### **4. Server starten**

**Windows:**
`ash
# Im Projektordner:
php -S localhost:8080
`

**Mac/Linux:**
`ash
# Im Projektordner:
php -S localhost:8080
`

**Browser öffnen:** http://localhost:8080

---

## 🆘 **Häufige Probleme**

### **"php ist nicht erkannt"**
- Problem: PHP ist nicht installiert
- Lösung: PHP herunterladen und installieren

### **"Port bereits verwendet" / "Address already in use"**
- Problem: Ein anderer Server läuft schon
- Lösung: Anderen Port verwenden:
  ```bash
  php -S localhost:8000   # Port 8000 probieren
  php -S localhost:3000   # Port 3000 probieren  
  php -S localhost:9000   # Port 9000 probieren
  ```

### **Dashboard zeigt keine Daten**
- Problem: Falsche Ordnerpfade in config.php
- Lösung: Prüfe die Ordnernamen in config.php

### **Seite lädt nicht**
- Problem: Server ist nicht gestartet
- Lösung: Terminal prüfen - steht da "Development Server started"?

### **Server stoppen**
- Windows/Mac/Linux: Ctrl + C im Terminal
- Oder: Terminal-Fenster schließen

---

**Erstellt für die TheBus Community** 🚌💙

**Version**: November 2025  
**Kompatibel mit**: TheBus Standard-Datenformat
