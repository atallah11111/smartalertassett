require('dotenv').config();
const { Client, LocalAuth } = require('whatsapp-web.js');
const qrcode = require('qrcode-terminal');
const express = require('express');
const bodyParser = require('body-parser');
const cors = require('cors');
const fs = require('fs');
const path = require('path');

const app = express();
const PORT = process.env.PORT || 3000;

app.use(cors());
app.use(bodyParser.json());

// ======= Cek Path Chrome Otomatis =======
const possiblePaths = [
  'C:\\Program Files\\Google\\Chrome\\Application\\chrome.exe',
  'C:\\Program Files (x86)\\Google\\Chrome\\Application\\chrome.exe',
  path.join(process.env.LOCALAPPDATA || '', 'Google\\Chrome\\Application\\chrome.exe')
];

const CHROME_PATH = possiblePaths.find(p => fs.existsSync(p)) || null;

if (!CHROME_PATH) {
  console.warn('⚠️ Chrome tidak ditemukan! Puppeteer akan mencoba Chromium bawaannya.');
}

// ======= Inisialisasi WhatsApp Client =======
const client = new Client({
  authStrategy: new LocalAuth(),
  puppeteer: {
    executablePath: CHROME_PATH || undefined,
    headless: false, // ubah ke true setelah berhasil login
    args: [
      '--no-sandbox',
      '--disable-setuid-sandbox',
      '--disable-dev-shm-usage',
      '--disable-extensions',
      '--disable-gpu',
      '--disable-software-rasterizer',
      '--window-size=1280,800'
    ]
  }
});

// ======= Event WhatsApp =======
client.on('qr', (qr) => {
  console.log('🔶 Scan QR ini dengan WhatsApp Web:');
  qrcode.generate(qr, { small: true });
});

client.on('ready', () => console.log('✅ WhatsApp client is ready!'));
client.on('authenticated', () => console.log('🔒 Client authenticated'));
client.on('auth_failure', (msg) => console.error('❌ Authentication failed:', msg));

client.on('disconnected', async (reason) => {
  console.log('⚠️ Disconnected:', reason);
  console.log('🔄 Restarting client...');
  try {
    await client.destroy();
    await client.initialize();
  } catch (err) {
    console.error('❌ Gagal restart client:', err);
  }
});

client.initialize();

// ======= Helper Functions =======
function formatNumber(number) {
  let formatted = number.toString().replace(/\D/g, '');
  if (formatted.startsWith('0')) formatted = '62' + formatted.slice(1);
  else if (!formatted.startsWith('62')) formatted = '62' + formatted;
  return formatted + '@c.us';
}

function delay(ms) {
  return new Promise((resolve) => setTimeout(resolve, ms));
}

async function isValidNumber(number) {
  try {
    const formatted = formatNumber(number);
    return await client.isRegisteredUser(formatted);
  } catch (err) {
    console.error('❌ Error cek nomor:', err);
    return false;
  }
}

// ======= Endpoint Kirim Pesan =======
app.post('/send-message', async (req, res) => {
  const { number, message, messages, delayMs = 1000 } = req.body;

  try {
    if (!client.info) {
      return res.status(500).json({ status: false, message: 'WA Client belum siap.' });
    }

    // === Bulk Message ===
    if (Array.isArray(messages)) {
      const results = [];

      for (const msg of messages) {
        try {
          if (!msg.number || !msg.message) {
            results.push({ number: msg.number || null, status: 'failed', error: 'Nomor atau pesan kosong.' });
            continue;
          }

          const valid = await isValidNumber(msg.number);
          if (!valid) {
            results.push({ number: msg.number, status: 'failed', error: 'Nomor tidak terdaftar di WhatsApp.' });
            continue;
          }

          const formatted = formatNumber(msg.number);
          const sent = await client.sendMessage(formatted, msg.message);
          results.push({ number: msg.number, status: 'success', response: sent });

          await delay(delayMs);
        } catch (err) {
          results.push({ number: msg.number, status: 'failed', error: err.toString() });
        }
      }

      return res.status(200).json({ status: true, results });
    }

    // === Single Message ===
    if (!number || !message) {
      return res.status(400).json({ status: false, message: 'Nomor dan pesan wajib diisi.' });
    }

    const valid = await isValidNumber(number);
    if (!valid) {
      return res.status(400).json({ status: false, message: 'Nomor tidak terdaftar di WhatsApp.' });
    }

    const formatted = formatNumber(number);
    const sentMessage = await client.sendMessage(formatted, message);

    return res.status(200).json({ status: true, response: sentMessage });

  } catch (err) {
    console.error('❌ Error kirim pesan:', err);
    return res.status(500).json({ status: false, error: err.toString() });
  }
});

// ======= Jalankan Express Server =======
app.listen(PORT, () => {
  console.log(`🟢 WA Service listening on port ${PORT}`);
});
