(function(){
  const usernameInput = document.getElementById('username');
  const apiUrlInput = document.getElementById('apiUrl');
  const connectBtn = document.getElementById('connectBtn');
  const messagesDiv = document.getElementById('messages');
  const textInput = document.getElementById('text');
  const sendBtn = document.getElementById('sendBtn');

  let apiBase = '';
  let username = '';
  let connected = false;
  let pollTimeout = null;

  function addMessage(m){
    const el = document.createElement('div');
    el.className = 'msg';
    el.innerHTML = `<div><strong>${escapeHtml(m.user)}</strong> <span class="meta">${new Date(m.ts).toLocaleTimeString()}</span></div><div>${escapeHtml(m.text)}</div>`;
    messagesDiv.appendChild(el);
    messagesDiv.scrollTop = messagesDiv.scrollHeight;
  }

  function escapeHtml(s){
    return String(s || '')
      .replace(/&/g,'&amp;')
      .replace(/</g,'&lt;')
      .replace(/>/g,'&gt;')
      .replace(/\"/g,'&quot;')
      .replace(/'/g,'&#039;');
  }

  function resolveApiBase(){
    const v = (apiUrlInput && apiUrlInput.value && apiUrlInput.value.trim());
    if(v) return v.replace(/\/$/, '');
    // default to current origin if available, else fallback to known host
    if(location && location.origin && location.origin !== 'null') return location.origin;
    return 'https://chat.platform4.me';
  }

  async function fetchInitial(){
    try{
      const res = await fetch(`${apiBase}/messages?limit=200`);
      if(!res.ok) throw new Error('Failed to fetch messages');
      const json = await res.json();
      messagesDiv.innerHTML = '';
      json.messages.reverse(); // show oldest first
      json.messages.forEach(m => addMessage({ id: m.id, user: m.uname || m.user || 'Anonymous', text: m.message || m.text, ts: new Date(m.ts).toISOString() }));
    }catch(err){
      console.warn('fetchInitial error', err);
    }
  }

  async function pollLoop(){
    if(!connected) return;
    try{
      const res = await fetch(`${apiBase}/messages?limit=200`);
      if(res.ok){
        const json = await res.json();
        // naive: clear and re-render
        messagesDiv.innerHTML = '';
        json.messages.reverse().forEach(m => addMessage({ id: m.id, user: m.uname || m.user || 'Anonymous', text: m.message || m.text, ts: new Date(m.ts).toISOString() }));
      }
    }catch(e){ console.warn('poll error', e); }
    pollTimeout = setTimeout(pollLoop, 2000);
  }

  connectBtn.addEventListener('click', async ()=>{
    if(!connected){
      // connect
      username = (usernameInput.value.trim() || 'Anonymous');
      apiBase = resolveApiBase();
      usernameInput.disabled = true;
      apiUrlInput.disabled = true;
      connectBtn.textContent = 'Trennen';
      connected = true;
      await fetchInitial();
      pollLoop();
    } else {
      // disconnect
      connected = false;
      connectBtn.textContent = 'Verbinden';
      usernameInput.disabled = false;
      apiUrlInput.disabled = false;
      if(pollTimeout) { clearTimeout(pollTimeout); pollTimeout = null; }
    }
  });

  sendBtn.addEventListener('click', ()=>{ sendMessage(); });
  textInput.addEventListener('keydown', (e)=>{ if(e.key === 'Enter') sendMessage(); });

  async function sendMessage(){
    const txt = textInput.value.trim();
    if(!txt || !username || !apiBase) return;
    try{
      const res = await fetch(`${apiBase}/message`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ message: txt, uname: username })
      });
      if(res.ok){
        const j = await res.json();
        // add new message to UI
        addMessage({ id: j.entry.id, user: j.entry.uname, text: j.entry.message, ts: new Date(j.entry.ts).toISOString() });
        textInput.value = '';
      } else {
        console.warn('send failed', res.status);
      }
    }catch(err){
      console.warn('send error', err);
    }
  }

})();
