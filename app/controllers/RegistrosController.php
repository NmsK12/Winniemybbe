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
            header('Location: ../index.php');
            exit;
        }
        
        // Verificar límites de PHP
        $max_file_size = ini_get('upload_max_filesize');
        $max_post_size = ini_get('post_max_size');
        
        // Log para debugging
        error_log("PHP Config - upload_max_filesize: " . $max_file_size);
        error_log("PHP Config - post_max_size: " . $max_post_size);
        error_log("File size: " . ($_FILES['cv']['size'] ?? 'N/A'));
        error_log("Content-Length: " . ($_SERVER['CONTENT_LENGTH'] ?? 'N/A'));
        
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $position = trim($_POST['position'] ?? '');

        if (!$name || !$email || !$position || !isset($_FILES['cv'])) {
            header('Location: ../index.php?err=' . urlencode('Faltan datos'));
            exit;
        }

        $file = $_FILES['cv'];
        
        // Manejo detallado de errores de subida
        $error_messages = [
            UPLOAD_ERR_INI_SIZE => 'El archivo excede el límite de upload_max_filesize (' . $max_file_size . ')',
            UPLOAD_ERR_FORM_SIZE => 'El archivo excede el límite MAX_FILE_SIZE del formulario',
            UPLOAD_ERR_PARTIAL => 'El archivo se subió parcialmente',
            UPLOAD_ERR_NO_FILE => 'No se seleccionó ningún archivo',
            UPLOAD_ERR_NO_TMP_DIR => 'Falta directorio temporal',
            UPLOAD_ERR_CANT_WRITE => 'No se pudo escribir el archivo al disco',
            UPLOAD_ERR_EXTENSION => 'Una extensión de PHP detuvo la subida del archivo'
        ];
        
        if ($file['error'] !== UPLOAD_ERR_OK) {
            $error_msg = $error_messages[$file['error']] ?? 'Error desconocido al subir archivo';
            header('Location: ../index.php?err=' . urlencode($error_msg));
            exit;
        }
        
        // Verificar Content-Length del POST
        $content_length = intval($_SERVER['CONTENT_LENGTH'] ?? 0);
        $max_post_bytes = 20 * 1024 * 1024; // 20MB en bytes
        
        if ($content_length > $max_post_bytes) {
            $error_msg = "El archivo es demasiado grande. Tamaño: " . round($content_length / 1024 / 1024, 2) . "MB, Límite: 20MB";
            header('Location: ../index.php?err=' . urlencode($error_msg));
            exit;
        }
        
        // Verificar tamaño del archivo
        if ($file['size'] > 20 * 1024 * 1024) { // 20MB
            header('Location: ../index.php?err=' . urlencode('El archivo es demasiado grande (máximo 20MB)'));
            exit;
        }

        $original = basename($file['name']);
        $ext = pathinfo($original, PATHINFO_EXTENSION);
        $stored = time() . '_' . bin2hex(random_bytes(6)) . ($ext ? '.' . $ext : '');
        $dest = $this->upload_dir . $stored;

        if (!move_uploaded_file($file['tmp_name'], $dest)) {
            header('Location: ../index.php?err=' . urlencode('No se pudo guardar el archivo'));
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
        header('Location: ../index.php?msg=' . urlencode('Postulación enviada correctamente'));
        exit;
    }
}
