<?php
session_start();
if (empty($_SESSION['admin'])) {
    header('Location: /login.php');
    exit;
}
require_once __DIR__ . '/app/models/Registro.php';

// handle delete action
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $id = (int) $_GET['id'];
    Registro::delete($id);
    header('Location: /admin.php?msg=' . urlencode('Registro eliminado'));
    exit;
}

$registros = Registro::all();
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Admin - Postulaciones</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="/public/css/styles.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-light">
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
      <a class="navbar-brand" href="/">Admin Panel</a>
      <div class="d-flex">
        <a class="btn btn-outline-light btn-sm me-2" href="/">Ver sitio</a>
        <a class="btn btn-outline-light btn-sm" href="/logout.php">Cerrar sesión</a>
      </div>
    </div>
  </nav>
  <main class="container py-5">
    <div class="card shadow-sm">
      <div class="card-body">
        <h4 class="mb-3">Postulantes</h4>
        <?php if (isset($_GET['msg'])): ?>
          <div class="alert alert-success"><?= htmlspecialchars($_GET['msg']) ?></div>
        <?php endif; ?>
        <div class="table-responsive">
          <table class="table table-hover">
            <thead class="table-light">
              <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Email</th>
                <th>Teléfono</th>
                <th>Puesto</th>
                <th>CV</th>
                <th>Fecha</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($registros)): ?>
                <?php foreach ($registros as $r): ?>
                  <tr>
                    <td><?= htmlspecialchars($r->id) ?></td>
                    <td><?= htmlspecialchars($r->name) ?></td>
                    <td><?= htmlspecialchars($r->email) ?></td>
                    <td><?= htmlspecialchars($r->phone) ?></td>
                    <td><?= htmlspecialchars($r->position) ?></td>
                    <td><a href="/uploads/<?= urlencode($r->stored_filename) ?>" download><?= htmlspecialchars($r->original_filename) ?></a></td>
                    <td><?= htmlspecialchars($r->uploaded_at) ?></td>
                    <td>
                      <button class="btn btn-danger btn-sm btn-delete" data-id="<?= $r->id ?>">Eliminar</button>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr><td colspan="8" class="text-center small text-muted">No hay postulantes aún.</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </main>
  <script src="/public/js/admin.js"></script>
</body>
</html>
