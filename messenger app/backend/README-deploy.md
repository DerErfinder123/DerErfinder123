Deployment & Docker — Simple Messenger

Lokales Starten ohne Docker

1. Installieren

```bash
cd "messenger app/backend"
npm install
```

2. Starten

```bash
npm start
# oder
./start.sh
```

Docker

1. Image bauen

```bash
cd "messenger app/backend"
docker build -t simple-messenger:latest .
```

2. Container starten (Port 3000 auf Host veröffentlichen)

```bash
docker run -p 3000:3000 --env-file .env --name simple-messenger simple-messenger:latest
```

Hinweise

- Aktuell werden Nachrichten nur im Arbeitsspeicher gehalten. Für Produktion persistente Speicherung (DB) hinzufügen.
- Setze Umgebungsvariablen in einer `.env`-Datei oder via `--env` beim `docker run`.
- Bei Bindings auf 0.0.0.0 (Docker) ist der Dienst im lokalen Netzwerk erreichbar.
