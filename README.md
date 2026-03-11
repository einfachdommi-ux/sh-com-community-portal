# SH-COM Portal

Vollständiges PHP/MySQL-Community-Portal ohne Composer, ausgelegt für PHP 8.3.

## Features

- Front Controller (`public/index.php`)
- MVC-/Controller-basierte Struktur
- Rollen & Permissions (RBAC)
- Admin Dashboard
- User Verwaltung
- Audit Log
- Mail Log
- MySQL-basierte Navigation
- Seitenverwaltung
- News-System
- Changelog-System
- Teamseite und Memberseite
- Registrierung mit E-Mail-Verifizierung
- SMTP-Versand ohne Composer
- Responsive UI mit Bootstrap 5
- `setup.php` Installer

## Installation

1. ZIP entpacken
2. Projekt auf den Server laden
3. Schreibrechte für `storage/` sicherstellen
4. `setup.php` im Browser öffnen
5. Datenbank- und SMTP-Zugang eintragen
6. `.htaccess`/Rewrite sicherstellen, dass `public/` die Webroot ist

## Standardpfade

- Frontend: `/`
- Login: `/login`
- Registrierung: `/register`
- Admin: `/admin`

## Sicherheitshinweise

Dieses Paket ist ein umfangreiches, funktionsfähiges Projektgerüst mit Kernfunktionen.
Vor Produktivbetrieb sollten zusätzlich erfolgen:

- Härtung der Serverkonfiguration
- HTTPS-Zwang
- Backup-/Restore-Konzept
- Mail-Deliverability-Prüfung (SPF, DKIM, DMARC)
- Penetration-Test / Code Review
- Cronjobs für Housekeeping
