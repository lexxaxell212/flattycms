<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sedang Maintenance — <?= SITE_NAME ?></title>
  <meta name="robots" content="noindex, nofollow">
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    body {
      min-height: 100svh;
      display: flex;
      align-items: center;
      justify-content: center;
      background: #f0f4f8;
      font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
      padding: 2rem;
    }

    .wrap {
      max-width: 480px;
      width: 100%;
      text-align: center;
    }

    .icon {
      font-size: 3.5rem;
      margin-bottom: 1.5rem;
      display: block;
      animation: float 3s ease-in-out infinite;
    }

    @keyframes float {
      0%, 100% { transform: translateY(0); }
      50%       { transform: translateY(-10px); }
    }

    .card {
      background: rgba(255,255,255,0.70);
      backdrop-filter: blur(18px);
      -webkit-backdrop-filter: blur(18px);
      border: 1px solid rgba(255,255,255,0.80);
      border-radius: 24px;
      padding: 2.5rem 2rem;
      box-shadow: 0 8px 32px rgba(31,60,120,0.10);
    }

    h1 {
      font-size: 1.5rem;
      font-weight: 700;
      color: #0f2f5e;
      letter-spacing: -0.02em;
      margin-bottom: 0.75rem;
    }

    p {
      font-size: 14px;
      color: #2563b0;
      line-height: 1.7;
      opacity: 0.75;
      margin-bottom: 1.5rem;
    }

    .badge {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 6px 14px;
      border-radius: 99px;
      background: rgba(37,99,176,0.08);
      border: 1px solid rgba(37,99,176,0.15);
      font-size: 12px;
      font-weight: 600;
      color: #2563b0;
      letter-spacing: 0.05em;
    }

    .dot {
      width: 6px;
      height: 6px;
      border-radius: 50%;
      background: #2563b0;
      animation: pulse 1.5s ease-in-out infinite;
    }

    @keyframes pulse {
      0%, 100% { opacity: 1; transform: scale(1); }
      50%       { opacity: 0.4; transform: scale(0.7); }
    }

    .footer {
      margin-top: 2rem;
      font-size: 12px;
      color: #5c9be3;
      opacity: 0.6;
    }
  </style>
</head>
<body>
  <div class="wrap">
    <span class="icon">🔧</span>
    <div class="card">
      <h1>Sedang dalam perbaikan</h1>
      <p>Coba lagi nanti.</p>
      <div class="badge">
        <span class="dot"></span>
        Sedang berlangsung
      </div>
    </div>
    <div class="footer"><?= SITE_NAME ?> · <?= date('Y') ?></div>
  </div>
</body>
</html>