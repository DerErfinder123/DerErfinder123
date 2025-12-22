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