<?php
require_once dirname(__DIR__, 2) . "/bootstrap.php";
autoload_core();

if (isset($_SESSION['admin_id'])) {
    header('Location: dashboard');
    exit;
}
// login
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username'], $_POST['password'])) {
    $stmt = $pdo->prepare("SELECT * FROM admins WHERE username = ?");
    $stmt->execute([$_POST['username']]);
    $admin = $stmt->fetch();
    
    if($admin && password_verify($_POST['password'], $admin['password'])) {
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_name'] = $admin['name'] ?? $admin['username'];
        header('Location: dashboard');
        exit;
    } else {
        $error = "Username atau password salah!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Login</title>
  <link href="<?= CSS_URL ?>bs533.min.css" rel="stylesheet">
  <style>
    body {
      background: #f8f9fa;
      min-height: 100vh;
      display: flex;
      align-items: center;
      padding: 0;
      margin: 0;
    }
    .login-card { 
      max-width: 420px;
      width: 100%;
      box-shadow: 0 0.5rem 2rem rgba(0,0,0,0.1);
      margin: 0 auto;
    }
    .login-header h1 { 
      color: #212529;
      font-weight: 700;
      font-size: 2rem;
      margin-bottom: 0.5rem;
    }
    .btn-login { 
      background: #0d6efd;
      border: none;
      color: white;
      border-radius: 0.5rem;
      padding: 0.75rem 1.5rem;
      font-size: 1rem;
      font-weight: 600;
      width: 100%;
    }
    .form-control {
      border-radius: 0.5rem;
      border: 1px solid #dee2e6;
      padding: 0.75rem 1rem;
    }
    .form-control:focus {
      border-color: #0d6efd;
      box-shadow: 0 0 0 0.2rem rgba(13,110,253,0.25);
    }
    @media (max-width: 576px) { 
      .login-card { margin: 1rem; } 
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-12 col-md-8 col-lg-5">
        <div class="card login-card border-0">
          <div class="card-body p-5">
            <!-- Header -->
            <div class="text-center mb-4">
              <h1 class="login-header">Admin Login</h1>
              <p class="text-muted mb-0">Masukkan kredensial Anda</p>
            </div>

            <!-- Error Alert -->
            <?php if(isset($error)): ?>
              <div class="alert alert-danger mb-4">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <?= htmlspecialchars($error) ?>
              </div>
            <?php endif; ?>

            <!-- Form Simpel -->
            <form method="POST">
              <div class="mb-3">
                <label class="form-label fw-semibold">Username</label>
                <input type="text" class="form-control" name="username" 
                       value="<?= isset($_POST['username']) ? htmlspecialchars($_POST['username']) : '' ?>" 
                       placeholder="Masukkan username" required autofocus>
              </div>

              <div class="mb-4">
                <label class="form-label fw-semibold">Password</label>
                <input type="password" class="form-control" name="password" 
                       placeholder="Masukkan password" required>
              </div>

              <button type="submit" class="btn btn-login mb-3">
                Masuk
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
