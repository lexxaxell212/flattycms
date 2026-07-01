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
  <link href="<?= CSS_URL ?>flatty1.2.1.min.css" rel="stylesheet">
</head>
<body>
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
          <?= htmlspecialchars($error) ?> error
        </div>
        <?php endif; ?>
        <form method="POST" style="background:none;border:none;box-shadow:none">
          <div class="card card-glass">
            <div class="card-body">
              <label class="form-label text-start">Username</label>
              <input type="text" class="form-control" name="username"
              value="<?= isset($_POST["username"])
                ? htmlspecialchars($_POST["username"])
                : "" ?>"
              placeholder="Your username" required autofocus>
              <label class="form-label fw-semibold text-start">Password</label>
              <input type="password" class="form-control" name="password"
              placeholder="••••••••••" required>
            </div>
            <div class="card-footer">
              <button type="submit" class="btn btn-primary w-100">
                Login
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </main>
</body>
</html>