<?php
if (
 $_SERVER["REQUEST_METHOD"] === "POST" &&
 isset($_POST["username"], $_POST["password"])
) {
 $stmt = $pdo->prepare("SELECT * FROM admins WHERE username = ?");
 $stmt->execute([$_POST["username"]]);
 $admin = $stmt->fetch();

 if ($admin && password_verify($_POST["password"], $admin["password"])) {
  $_SESSION["admin_id"] = $admin["id"];
  $_SESSION["admin_name"] = $admin["name"] ?? $admin["username"];
  header("Location: /admin/");
  exit();
 } else {
  $error = "Username atau password salah!";
 }
} ?>

<!DOCTYPE html>
<html lang="en">
<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>Login Page</title>
 <link href="<?= CSS_URL ?>bs538.min.css" rel="stylesheet">
 <link href="<?= CSS_URL ?>flatty1.2.2.min.css" rel="stylesheet">
 <style>
  .reveal-overlay {
   position: fixed;
   inset: 0;
   background: var(--sl-900);
   z-index: 9999;
   cursor: default;
   overflow: hidden;
   transition: opacity 0.6s ease;
   touch-action: none;
   user-select: none;
   -webkit-user-select: none;
  }

  .reveal-overlay.hidden {
   opacity: 0;
   pointer-events: none;
  }

  .sparkle-text {
   position: fixed;
   pointer-events: none;
   font-size: 18px;
   font-weight: 600;
   color: #fff;
   text-shadow: 0 0 8px var(--purple-400), 0 0 16px var(--purple-600);
   z-index: 10000;
   transform: translate(-50%, -140%);
   animation: sparkleFloat 0.9s ease-out forwards;
  }

  @keyframes sparkleFloat {
   0% {
    opacity: 0;
    transform: translate(-50%, -120%) scale(0.6);
   }
   20% {
    opacity: 1;
    transform: translate(-50%, -150%) scale(1.05);
   }
   100% {
    opacity: 0;
    transform: translate(-50%, -190%) scale(0.9);
   }
  }

  .sparkle-text::before,
  .sparkle-text::after {
   content: '✦';
   position: absolute;
   color: var(--purple-500);
   font-size: 10px;
   animation: twinkle 0.9s ease-in-out infinite;
  }
  .sparkle-text::before {
   left: -18px;
   top: -4px;
   animation-delay: 0.1s;
  }
  .sparkle-text::after {
   right: -18px;
   top: 6px;
   animation-delay: 0.35s;
  }

  @keyframes twinkle {
   0%, 100% {
    opacity: 0.2;
    transform: scale(0.8);
   }
   50% {
    opacity: 1;
    transform: scale(1.2);
   }
  }

  .sparkle-success {
   position: fixed;
   pointer-events: none;
   font-size: 22px;
   font-weight: 700;
   color: var(--text-primary);
   text-shadow: 0 0 10px var(--purple-400), 0 0 22px var(--purple-600);
   z-index: 10001;
   transform: translate(-50%, -50%);
   animation: successPop 0.7s ease-out forwards;
  }

  @keyframes successPop {
   0% {
    opacity: 0;
    transform: translate(-50%, -50%) scale(0.4);
   }
   40% {
    opacity: 1;
    transform: translate(-50%, -50%) scale(1.15);
   }
   100% {
    opacity: 0;
    transform: translate(-50%, -50%) scale(1.4);
   }
  }
 </style>
</head>
<body>

 <div class="reveal-overlay" id="revealOverlay"></div>
 <main class="main-content">
  <div class="container">
   <div class="page-header">
    <div class="text-center">
     <h1 class="h2">Welcome to <em class="styled">Flatty Dashboard</em></h1>
    </div>
   </div>
   <div class="mx-auto" style="max-width:440px">
    <?php if (isset($error)): ?>
    <div class="badge badge-red mx-auto d-block" style="width:fit-content">
     <i class="fas fa-exclamation-triangle me-2"></i>
     <?= htmlspecialchars($error) ?>
    </div>
    <?php endif; ?>
    <form method="POST" style="background:none;border:none;box-shadow:none">
     <div class="card card-flatty">
      <div class="card-body">
       <input type="text" class="form-control mb-4" name="username"
       value="<?= isset($_POST["username"])
       ? htmlspecialchars($_POST["username"])
       : "" ?>"
       placeholder="Username" required autofocus>
       <input type="password" class="form-control" name="password"
       placeholder="••••••••••" required>
      </div>
      <div class="card-footer">
       <button type="submit" class="btn btn-primary btn-fit">
        Login
       </button>
      </div>
     </div>
    </form>
   </div>
  </div>
 </main>

 <script>
  (function () {
   const overlay = document.getElementById('revealOverlay');
   const HOLD_DURATION = 5000;
   let holdTimeout = null;
   let sparkleThrottle = 0;

   function spawnSparkle(x, y) {
    const now = performance.now();
    if (now - sparkleThrottle < 220) return;
    sparkleThrottle = now;

    const el = document.createElement('div');
    el.className = 'sparkle-text';
    el.textContent = 'where';
    el.style.left = x + 'px';
    el.style.top = y + 'px';
    overlay.appendChild(el);
    setTimeout(() => el.remove(), 1000);
   }

   overlay.addEventListener('mousemove', (e) => spawnSparkle(e.clientX, e.clientY));
   overlay.addEventListener('touchmove', (e) => {
    const t = e.touches[0];
    if (t) spawnSparkle(t.clientX, t.clientY);
   },
    {
     passive: true
    });

   function startHold(x,
    y) {
    if (holdTimeout) return;
    holdTimeout = setTimeout(() => {
     showSuccessSparkle(x, y);
     revealPage();
    }, HOLD_DURATION);
   }

   function cancelHold() {
    if (holdTimeout) {
     clearTimeout(holdTimeout);
     holdTimeout = null;
    }
   }

   function showSuccessSparkle(x, y) {
    const el = document.createElement('div');
    el.className = 'sparkle-success';
    el.textContent = '✦';
    el.style.left = x + 'px';
    el.style.top = y + 'px';
    overlay.appendChild(el);
    setTimeout(() => el.remove(), 700);
   }

   function revealPage() {
    setTimeout(() => overlay.classList.add('hidden'), 300);
   }

   overlay.addEventListener('mousedown', (e) => startHold(e.clientX, e.clientY));
   overlay.addEventListener('mouseup', cancelHold);
   overlay.addEventListener('mouseleave', cancelHold);

   overlay.addEventListener('touchstart', (e) => {
    const t = e.touches[0];
    if (t) startHold(t.clientX, t.clientY);
   },
    {
     passive: true
    });
   overlay.addEventListener('touchend',
    cancelHold);
   overlay.addEventListener('touchcancel',
    cancelHold);
  })();
 </script>

</body>
</html>