# Swiss Hiking Tracks

Dieses Projekt ist eine Webanwendung zur Verwaltung von Wanderrouten in der Schweiz.

Zum Planen von Wanderrouten gibt es bereits verschiedene bestehende Tools und Hilfsmittel. So beispielsweise der Kartenviewer des Bundes, das kostenpflichtige Tool «SchweizMobil Plus», sowie die App des Bundesamts für Landestopografie swisstopo, welche von Ubique entwickelt wurde. Die Funktionalitäten dieser Tools (Einzeichnen von Wanderrouten auf einer Karte, Darstellen des öffentlichen Wanderwegs-Netz, …) wird nicht ersetzt, sondern ergänzt. Alle zuvor erwähnten Tools besitzen unserer Meinung nach keine überzeugende Funktionalität, um eine grosse Anzahl an Wanderrouten zu verwalten und den Überblick darüber zu behalten, und dabei noch ein ansprechendes Design zu besitzen, welches auch motiviert, die Wanderungen tatsächlich auszuprobieren und durchzuführen.

## Lokale Installation
Zur lokalen Installation sind verschiedene Schritte notwendig.

### Vorbereitungen
Stellen Sie sicher, dass Sie die folgenden Tools bereits installiert haben:
* [git](https://git-scm.com/book/en/v2/Getting-Started-Installing-Git)
* [Node.js und npm](https://docs.npmjs.com/downloading-and-installing-node-js-and-npm)
* php (unter Windows beispielsweise mit [XAMPP](https://www.apachefriends.org/de/index.html))
* [composer](https://getcomposer.org/download/)

#### Windows
Stellen Sie sicher, dass git, npm, php und composer in den Umgebungsvariablen (PATH) eingetragen und somit in allen Terminals aufrufbar sind.
Starten Sie Ihr Gerät nach der Installation neu.

### Installation
Folgen Sie diesen Schritten, um das Projekt lokal zu installieren:

1. Klonen Sie das Repository: (oder kopieren Sie alternativ alle Dateien in einen Ordner)
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
> Die .env-Datei beinhaltet die Umgebungsvariabeln (bspw. Datenbank-Zugangsdaten) für die Applikation.

5. Generieren Sie einen Anwendungsschlüssel:
```bash
php artisan key:generate
```
> Dieser Befehl erstellt einen Anwendungsschlüssel und fügt diesen in die .env-Datei ein. Dieser Anwendungsschlüssel dient zur Verschlüsselung, ...

6. Führen Sie die Datenbankmigrationen aus:
```bash
php artisan migrate
``` 
> Dieser Befehl erstellt die Datenbanktabellen für die Applikation.

7. Erstellen Sie einen symbolischen Link für das `storage` Verzeichnis:
```bash
php artisan storage:link
```
> Dieser Befehl erstellt einen symbolischen Link vom public/storage Verzeichnis zu storage/app/public, was es ermöglicht, auf Dateien im storage/app/public Verzeichnis über die URL `http://localhost:8000/storage` zuzugreifen. Dies ist notwendig, da die Anwendung in diesem Verzeichnis von den Nutzer*innen hochgeladene Dateien abspeichert.

8. Starten Sie den Entwicklungsserver: (in zwei verschiedenen Terminals)
```bash
php artisan serve
```
```bash
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