<?php
require_once __DIR__ . '/../models/Registro.php';

class RegistrosController {
    private $upload_dir;

    public function __construct() {
        $this->upload_dir = __DIR__ . '/../../uploads/';
        if (!is_dir($this->upload_dir)) {
            @mkdir($this->upload_dir, 0777, true);
        }
    }

    // handle public form upload
    public function upload() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /');
            exit;
        }
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $position = trim($_POST['position'] ?? '');

        if (!$name || !$email || !$position || !isset($_FILES['cv'])) {
            header('Location: /?err=' . urlencode('Faltan datos'));
            exit;
        }

        $file = $_FILES['cv'];
        if ($file['error'] !== UPLOAD_ERR_OK) {
            header('Location: /?err=' . urlencode('Error subiendo archivo'));
            exit;
        }

        $original = basename($file['name']);
        $ext = pathinfo($original, PATHINFO_EXTENSION);
        $stored = time() . '_' . bin2hex(random_bytes(6)) . ($ext ? '.' . $ext : '');
        $dest = $this->upload_dir . $stored;

        if (!move_uploaded_file($file['tmp_name'], $dest)) {
            header('Location: /?err=' . urlencode('No se pudo guardar el archivo'));
            exit;
        }

        $id = Registro::nextId();
        $uploaded_at = date('Y-m-d H:i:s');
        $reg = (object)[
            'id' => $id,
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'position' => $position,
            'original_filename' => $original,
            'stored_filename' => $stored,
            'uploaded_at' => $uploaded_at
        ];
        Registro::save($reg);
        header('Location: /?msg=' . urlencode('Postulación enviada correctamente'));
        exit;
    }
}
