Simple Messenger

**Projekt:**
- **Beschreibung:** Minimaler Web-Messenger mit Node.js/Express. Enthält ein einfaches REST-API-Backend und ein leichtes Frontend im Ordner `backend/public`.

**Schnellstart (lokal):**
- **Schritt 1 — Abhängigkeiten:**
   - `cd "messenger app/backend"`
   - `npm install`
- **Schritt 2 — Server starten:**
   - `npm start`
   - Öffne im Browser: `http://localhost:3000`

**Frontend (UI):**
- **Datei:** [backend/public/index.html](backend/public/index.html)
- **Beschreibung:** UI erlaubt Eingabe von Benutzername und optionaler Server-URL. Sendet Nachrichten per `POST /message` und ruft Chat-Historie per `GET /messages` ab (Polling).

**API (REST):**
- **POST /message** — Sende eine Nachricht
   - Body: `{"message":"Text","uname":"Benutzer"}`
   - Antwort: `{ ok: true, entry: { id, message, uname, ts } }`
- **GET /messages?limit=N** — Lade die letzten N Nachrichten (Standard 50, max 200)
   - Antwort: `{ ok: true, messages: [...] }`
- **GET /** — Healthcheck: `{ ok: true, service: "chat-api" }`

**Deployment / Production Hinweise:**
- **Umgebungsvariablen:** siehe `backend/.env.example` — z.B. `PORT`, `NODE_ENV`.
- **Docker:** Baue das Image im `backend`-Verzeichnis:
   - `docker build -t simple-messenger:latest .`
   - `docker run -p 3000:3000 --env-file .env --name simple-messenger simple-messenger:latest`
- **Wichtig:** Nachrichten werden aktuell nur im Arbeitsspeicher gehalten — für Produktion persistente Speicherung (z. B. SQLite/Postgres) und Auth hinzufügen.

**Logs & Hintergrundbetrieb:**
- Lokaler Start (`npm start`) gibt Logs in die Konsole aus.
- Für Background-Betrieb verwende `pm2`, `systemd` oder Docker. Beispiel mit `nohup`:
   - `nohup node index.js > server.log 2>&1 &`

**Entwicklung & Änderungen:**
- **Code:** Server: [backend/index.js](backend/index.js) — Frontend: [backend/public/main.js](backend/public/main.js)
- **Testen:** Öffne mehrere Browser-Fenster/Inkognito-Tabs, verbinde mit verschiedenen Benutzernamen und sende Nachrichten.

**Kontakt / Weiteres:**
- Wenn du möchtest, kann ich:
   - Polling zu Server-Sent Events upgraden
   - Realtime via separatem WebSocket-Server hinzufügen (ohne `server.js` zu ändern)
   - Persistenz (SQLite/Postgres) integrieren

Hinweis: Dies ist eine Beispiel-/Entwicklungs-Implementierung — für öffentliche Produktion sind zusätzliche Sicherheitsmaßnahmen (Input-Validation, Auth, Rate-Limiting, HTTPS, CSP) erforderlich.

**pm2 — empfohlen für Dauerbetrieb**

`pm2` ist ein einfacher Prozessmanager für Node.js-Anwendungen. Er startet Prozesse neu, speichert die Prozessliste und kann beim Systemstart automatisch wiederhergestellt werden.

Kurzanleitung:

```bash
# Installiere pm2 global
npm install -g pm2

# Im Projektverzeichnis
cd "messenger app/backend"

# Starte die App mit pm2
pm2 start index.js --name messenger

# Zeige Logs
pm2 logs messenger

# Prozesse dauerhaft speichern (damit pm2 beim Reboot wieder startet)
pm2 save
pm2 startup
```

Hinweis: `pm2 startup` gibt einen Befehl aus, den du einmal mit Root-Rechten ausführen musst (pm2 zeigt ihn nach `pm2 startup` an).


**Terminal-Befehl zum Starten (Beispiele)**

- Einfach (debug/Foreground):
```
cd "messenger app/backend"
npm start
```

- Im Hintergrund mit `nohup` und PID/Log-Datei (einfaches, eigenes Management):
```
cd "messenger app/backend"
# stoppe Prozess auf Port 3000 falls vorhanden, starte im Hintergrund, schreibe PID und Log
rm -f server.pid || true
pid=$(lsof -ti TCP:3000 -sTCP:LISTEN)
if [ -n "$pid" ]; then kill $pid; fi
nohup npm start > server.log 2>&1 & echo $! > server.pid
tail -f server.log
```

Hinweis: Für stabilen Dauerbetrieb empfehle ich `pm2` oder Docker (siehe README oben).

**Server-Code (`server.js`)**

Der folgende Code zeigt den einfachen REST-Server, der in diesem Repository verwendet wird:

```
const express = require("express");
const app = express();

app.use(express.json());

// In-memory message store
const messages = [];
const MAX_MESSAGES = 500;

// POST /message
// Body: { "message": "Hello", "uname": "Fabi" }
app.post("/message", (req, res) => {
   const { message, uname } = req.body;

   if (typeof message !== "string" || typeof uname !== "string") {
      return res.status(400).json({
         ok: false,
         error: 'Send JSON like { "message": "hi", "uname": "User" }'
      });
   }

   const entry = {
      id: Math.random().toString(36).slice(2),
      message: message.trim(),
      uname: uname.trim(),
      ts: Date.now()
   };

   messages.unshift(entry);
   if (messages.length > MAX_MESSAGES) messages.pop();

   res.json({ ok: true, entry });
});

// GET /messages
app.get("/messages", (req, res) => {
   const limit = Math.min(Number(req.query.limit) || 50, 200);
   res.json({ ok: true, messages: messages.slice(0, limit) });
});

// Health check
app.get("/", (req, res) => {
   res.json({ ok: true, service: "chat-api" });
});

const PORT = 3000;
app.listen(PORT, () => {
   console.log(`Chat API running on http://localhost:${PORT}`);
});
```

