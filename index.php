<?php
require_once __DIR__ . '/app/controllers/RegistrosController.php';
$controller = new RegistrosController();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller->upload();
}
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Postula - Ingeniero de Sistemas</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="/public/css/styles.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container">
    <a class="navbar-brand" href="/">Portal de Postulaciones</a>
    <div class="d-flex">
      <a class="btn btn-outline-light btn-sm" href="/login.php">Admin</a>
    </div>
  </div>
</nav>
<main class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card shadow-sm">
        <div class="card-body p-4">
          <h3 class="mb-3">Postúlate a una vacante</h3>
          <?php if (isset($_GET['msg'])): ?>
            <div class="alert alert-success"><?= htmlspecialchars($_GET['msg']) ?></div>
          <?php endif; ?>
          <?php if (isset($_GET['err'])): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($_GET['err']) ?></div>
          <?php endif; ?>
          <form id="apply-form" method="post" enctype="multipart/form-data" novalidate>
            <div class="mb-3">
              <label class="form-label">Nombre completo</label>
              <input type="text" name="name" class="form-control" required>
            </div>
            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label">Correo electrónico</label>
                <input type="email" name="email" class="form-control" required>
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">Teléfono</label>
                <input type="text" name="phone" class="form-control">
              </div>
            </div>
            <div class="mb-3">
              <label class="form-label">Puesto</label>
              <select name="position" class="form-select" required>
                <option value="">-- Selecciona --</option>
                <option>Ingeniero de Sistemas</option>
                <option>Analista de Sistemas</option>
                <option>Desarrollador Backend</option>
                <option>Desarrollador Frontend</option>
                <option>DevOps</option>
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label">CV (pdf, docx, zip, etc.)</label>
              <input type="file" name="cv" class="form-control" required>
            </div>
            <div class="d-flex justify-content-end">
              <button class="btn btn-primary" type="submit">Enviar postulación</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</main>
<script src="/public/js/app.js"></script>
</body>
</html>
