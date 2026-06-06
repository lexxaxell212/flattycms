const API_URL = "/api/api-assistant.php";
const TOPIC   = "bandung";
const TIMEOUT = 20000;

let conversationHistory = [];
let isLoading = false;

function escapeHTML(str) {
  return str
    .replace(/&/g, "&amp;")
    .replace(/</g, "&lt;")
    .replace(/>/g, "&gt;")
    .replace(/"/g, "&quot;")
    .replace(/'/g, "&#039;");
}

function formatText(text) {
  return escapeHTML(text).replace(/\n/g, "<br>");
}

function currentTime() {
  return new Date().toLocaleTimeString([], { hour: "2-digit", minute: "2-digit" });
}

function scrollToBottom() {
  const messages = document.getElementById("chat-messages");
  messages.scrollTop = messages.scrollHeight;
}

function removeLoadingBubble(id) {
  document.getElementById(id)?.remove();
}

function addMessage(text, type = "loading") {
  const messages = document.getElementById("chat-messages");
  const wrap = document.createElement("div");

  wrap.id        = `msg_${Date.now()}_${Math.random().toString(36).slice(2, 6)}`;
  wrap.className = `message ${type}`;

  const bubble = document.createElement("div");
  bubble.className = "bubble";

  if (type === "loading") {
    bubble.innerHTML = `
      <div class="typing-indicator">
        <span></span><span></span><span></span>
      </div>`;
  } else if (type === "error") {
    bubble.innerHTML = `<span class="error-text">⚠️ ${escapeHTML(text)}</span>`;
  } else {
    const label  = type === "user" ? "You" : "Yara";
    bubble.innerHTML = `
      <div class="msg-label">${label}</div>
      <div class="msg-text">${formatText(text)}</div>
      <div class="msg-time">${currentTime()}</div>`;
  }

  wrap.appendChild(bubble);
  messages.appendChild(wrap);
  scrollToBottom();

  return wrap.id;
}

async function sendMessage() {
  if (isLoading) return;

  const input   = document.getElementById("message-input");
  const message = input.value.trim();

  if (!message) return;

  addMessage(message, "user");
  input.value = "";
  input.style.height = "auto"; 
  input.focus();

  conversationHistory.push({ role: "user", content: message });

  const loadingId = addMessage("", "loading");
  isLoading = true;

  try {
    const controller = new AbortController();
    const timeout    = setTimeout(() => controller.abort(), TIMEOUT);

    const response = await fetch(API_URL, {
      method:  "POST",
      headers: { 
        "Content-Type": "application/json",
        "X-Requested-With": "XMLHttpRequest",
      },
      body:    JSON.stringify({ message, topic: TOPIC, history: conversationHistory }),
      signal:  controller.signal
    });

    clearTimeout(timeout);
    const data = await response.json();
    removeLoadingBubble(loadingId);

    if (data.success) {
      addMessage(data.reply, "ai");
      conversationHistory.push({ role: "assistant", content: data.reply });
    } else {
      addMessage(data.error ?? "Terjadi kesalahan.", "error");
      conversationHistory.pop();
    }
  } catch (err) {
    removeLoadingBubble(loadingId);
    const msg = err.name === "AbortError"
      ? "Request timeout."
      : "Koneksi bermasalah. Periksa jaringanmu.";
    addMessage(msg, "error");
    conversationHistory.pop();
  } finally {
    isLoading = false;
  }
}

function clearChat() {
  document.getElementById("chat-messages").innerHTML = "";
  conversationHistory = [];
  addMessage("Chat dibersihkan. Ada yang bisa Yara bantu lagi? 😊", "ai");
}

function autoResize(el) {
  el.style.height = "auto";
  el.style.height = Math.min(el.scrollHeight, 120) + "px"; // max 120px
}

document.addEventListener("DOMContentLoaded", () => {
  const input = document.getElementById("message-input");

  input.addEventListener("keydown", (e) => {
    if (e.key === "Enter" && !e.shiftKey) {
      e.preventDefault();
      sendMessage();
    }
  });

  input.addEventListener("input", () => autoResize(input));

  addMessage("Wilujeng sumping! 👋 Yara siap bantu eksplor Bandung. Mau tanya apa nih..?", "ai");
  input.focus();
});