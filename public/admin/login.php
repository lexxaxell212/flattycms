<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username'], $_POST['password'])) {
  $stmt = $pdo->prepare("SELECT * FROM admins WHERE username = ?");
  $stmt->execute([$_POST['username']]);
  $admin = $stmt->fetch();

  if ($admin && password_verify($_POST['password'], $admin['password'])) {
    $_SESSION['admin_id'] = $admin['id'];
    $_SESSION['admin_name'] = $admin['name'] ?? $admin['username'];
    header('Location: /admin/');
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
  <link href="<?= CSS_URL ?>flattypurple.css" rel="stylesheet">
  <link href="<?= CSS_URL ?>flattyui.css" rel="stylesheet">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }
    body {
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-12 col-md-8 col-lg-5">
        <div class="card card-glass">
          <div class="card-body">
            <div class="text-center">
              <h1 class="text-title">Admin Login</h1>
              <p class="text-muted">
                Masukkan kredensial Anda
              </p>
            </div>
            <!-- Error Alert -->
            <?php if (isset($error)): ?>
            <div class="alert alert-danger mb-4">
              <i class="fas fa-exclamation-triangle me-2"></i>
              <?= htmlspecialchars($error) ?>
            </div>
            <?php endif; ?>
            <form method="POST">
              <div class="mb-3">
                <label class="form-label fw-semibold text-start">Username</label>
                <input type="text" class="form-control" name="username"
                value="<?= isset($_POST['username']) ? htmlspecialchars($_POST['username']) : '' ?>"
                placeholder="Masukkan username" required autofocus>
              </div>
              <div class="mb-4">
                <label class="form-label fw-semibold text-start">Password</label>
                <input type="password" class="form-control" name="password"
                placeholder="Masukkan password" required>
              </div>
              <button type="submit" class="btn btn-primary mb-3">
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