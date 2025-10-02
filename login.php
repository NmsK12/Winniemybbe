<?php
session_start();
$err = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $users = json_decode(file_get_contents(__DIR__ . '/data/users.json'), true);
    $u = $_POST['username'] ?? '';
    $p = $_POST['password'] ?? '';
    $found = false;
    foreach ($users as $user) {
        if ($user['username'] === $u && $user['password'] === $p) {
            $found = true;
            break;
        }
    }
    if ($found) {
        $_SESSION['admin'] = true;
        header('Location: /admin.php');
        exit;
    } else {
        $err = 'Credenciales incorrectas';
    }
}
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Login Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="/public/css/styles.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="d-flex vh-100 align-items-center justify-content-center">
    <div class="card p-4 shadow-sm" style="width:360px;">
      <h5 class="mb-3">Acceso Admin</h5>
      <?php if ($err): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($err) ?></div>
      <?php endif; ?>
      <form method="post">
        <div class="mb-3">
          <label class="form-label">Usuario</label>
          <input class="form-control" name="username" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Contraseña</label>
          <input class="form-control" name="password" type="password" required>
        </div>
        <div class="d-flex justify-content-end">
          <button class="btn btn-primary">Entrar</button>
          <a class="btn btn-outline btn-sm" href="/index.php">Registrar</a>
        </div>
      </form>
    </div>
  </div>
</body>
</html>
