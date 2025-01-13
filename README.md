# Mein Kontaktbuch

Ein einfaches digitales Kontaktbuch, das es ermöglicht, Kontakte zu verwalten. Es bietet Funktionen wie Anmeldung, Kontaktanzeige, Hinzufügen und Löschen von Kontakten sowie grundlegende Zugriffsbeschränkungen.

## Funktionen

### 1. **Startseite**
- Begrüßung der Nutzer.
- Kurze Erklärung der Funktionen des Kontaktbuchs.

### 2. **Login/Logout**
- **Einloggen**: 
  - Nutzer geben ihren Benutzernamen und ihr Passwort ein, um auf geschützte Bereiche zuzugreifen.
  - Standard-Zugangsdaten:
    - Benutzername: `user`
    - Passwort: `password`
- **Logout**: 
  - Beendet die aktuelle Sitzung und entzieht alle Rechte.
  - Nach dem Logout ist ein erneutes Einloggen jederzeit möglich.

### 3. **Kontakte verwalten**
- **Kontakte anzeigen**:
  - Zeigt eine Liste aller gespeicherten Kontakte an.
  - Kontakte werden als Karten mit Namen und Telefonnummer dargestellt.
  - Optionen für jeden Kontakt:
    - **Anrufen**: Direktes Wählen der Telefonnummer.
    - **Löschen**: Entfernt den Kontakt aus der Liste.
- **Kontakte hinzufügen**:
  - Möglichkeit, neue Kontakte hinzuzufügen.
  - Eingabefelder für Name und Telefonnummer.
  - Daten werden sicher (gegen XSS geschützt) verarbeitet und in einer Datei (`contacts.txt`) gespeichert.
- **Kontakte löschen**:
  - Entfernt einen ausgewählten Kontakt aus der Liste.
  - Aktualisiert die gespeicherte Datei, um die Änderungen dauerhaft zu machen.

### 4. **Zugriffsbeschränkungen**
- Geschützte Bereiche (z. B. Kontaktmanagement) sind nur nach erfolgreichem Login zugänglich.
- Nicht eingeloggte Nutzer sehen stattdessen eine Meldung mit der Aufforderung, sich einzuloggen.

### 5. **Impressum**
- Zeigt Kontaktinformationen des Verantwortlichen an, inklusive Haftungsausschluss.

## Aufbau der Seiten
- **index.php** steuert die gesamte Seitenlogik basierend auf dem `page`-Parameter.
- Unterstützte Seiten:
  - `start`: Startseite
  - `login`: Login-Bereich
  - `contacts`: Kontaktverwaltung
  - `addcontact`: Hinzufügen von Kontakten
  - `legal`: Impressum

## Sicherheit
- Schutz vor Cross-Site-Scripting (XSS) durch Nutzung von `htmlspecialchars` für Nutzereingaben.
- Sessions werden verwendet, um den Login-Status zu speichern.

## Installation
1. Stelle sicher, dass ein PHP-Server installiert ist.
2. Kopiere die Dateien in den Dokumenten-Root deines Servers.
3. Starte den Server und öffne die Anwendung über den Browser.

## Verwendete Technologien
- **PHP**: Logik und Session-Management.
- **HTML/CSS**: Benutzeroberfläche.
- **JSON**: Speicherung von Kontaktdaten.

## Lizenz
Diese Anwendung wurde erstellt von **Marcus Moser** und steht unter keinem speziellen Lizenzmodell. Änderungen sind erlaubt.
