# Swiss Hiking Tracks

Dieses Projekt ist eine Webanwendung zur Verwaltung von Wanderwegen in der Schweiz.

## Installation

Folgen Sie diesen Schritten, um das Projekt lokal zu installieren:

1. Klonen Sie das Repository:

```bash
git clone https://github.com/Timeo04/swiss-hiking-tracks.git
```
2. Installieren Sie die PHP-Abhängigkeiten:
```bash
composer install
```
3. Installieren Sie die Node.js-Abhängigkeiten:
```bash
npm install
```
4. Kopieren Sie die .env.example Datei und benennen Sie sie in .env um:
```bash
cp .env.example .env
```
5. Generieren Sie einen Anwendungsschlüssel:
```bash
php artisan key:generate
```
6. Führen Sie die Datenbankmigrationen aus:
```bash
php artisan migrate
``` 
7. Starten Sie den Entwicklungsserver: (in zwei verschiedenen Terminals)
```bash
php artisan serve
npm run dev
``` 
Jetzt sollten Sie in der Lage sein, die Anwendung in Ihrem Webbrowser unter ```http://localhost:8000``` zu öffnen.

## Tests
Um die Tests auszuführen, verwenden Sie den folgenden Befehl:
```bash
php artisan test
```

## Projekt
Dieses Projekt entsteht im Rahmen des Ergänzungsfach Informatik.
Lea, Yannis, Cedric