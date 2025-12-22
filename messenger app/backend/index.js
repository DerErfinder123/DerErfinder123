const express = require('express');
const http = require('http');
const { Server } = require('socket.io');
const path = require('path');
const config = require('./config');

const app = express();

// Wenn hinter einem Reverse-Proxy (z.B. nginx, Cloudflare) laufen, trust the proxy
app.set('trust proxy', true);

// Leite HTTP auf HTTPS weiter, falls Proxy X-Forwarded-Proto sendet
app.use((req, res, next) => {
  const proto = req.headers['x-forwarded-proto'];
  if (proto && proto.split(',')[0] !== 'https') {
    return res.redirect('https://' + req.headers.host + req.url);
  }
  next();
});

const server = http.createServer(app);

// Socket.IO CORS: erlaubte Origin per ENV `CORS_ORIGIN` oder Standard auf '*'
const corsOrigin = process.env.CORS_ORIGIN || '*';
const io = new Server(server, {
  cors: {
    origin: corsOrigin,
    methods: ['GET', 'POST'],
    credentials: true
  }
});

const PORT = config.port || 3000;
const messages = [];

app.use(express.static(path.join(__dirname, 'public')));

io.on('connection', (socket) => {
  socket.on('join', (username) => {
    socket.username = username || 'Anonymous';
    socket.emit('init', messages);
  });

  socket.on('message', (data) => {
    const msg = {
      id: Date.now() + Math.random().toString(36).slice(2, 7),
      user: socket.username || data.user || 'Anonymous',
      text: (data && data.text) || '',
      ts: new Date().toISOString()
    };
    messages.push(msg);
    if (messages.length > 200) messages.shift();
    io.emit('message', msg);
  });

  socket.on('disconnect', () => {});
});

server.listen(PORT, config.host, () => console.log(`Server listening on http://${config.host}:${PORT}`));
