<?php
class Registro {
    private static $storage = __DIR__ . '/../../data/storage.json';

    public $id;
    public $name;
    public $email;
    public $phone;
    public $position;
    public $original_filename;
    public $stored_filename;
    public $uploaded_at;

    public function __construct($id, $name, $email, $phone, $position, $original_filename, $stored_filename, $uploaded_at) {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->phone = $phone;
        $this->position = $position;
        $this->original_filename = $original_filename;
        $this->stored_filename = $stored_filename;
        $this->uploaded_at = $uploaded_at;
    }

    public static function all() {
        if (!file_exists(self::$storage)) return [];
        $json = file_get_contents(self::$storage);
        $arr = json_decode($json, true);
        if (!is_array($arr)) return [];
        $out = [];
        foreach ($arr as $item) {
            $out[] = (object)$item;
        }
        return $out;
    }

    public static function save($registro) {
        $arr = [];
        if (file_exists(self::$storage)) {
            $arr = json_decode(file_get_contents(self::$storage), true) ?: [];
        } else {
            @mkdir(dirname(self::$storage), 0777, true);
        }
        $arr[] = [
            'id' => $registro->id,
            'name' => $registro->name,
            'email' => $registro->email,
            'phone' => $registro->phone,
            'position' => $registro->position,
            'original_filename' => $registro->original_filename,
            'stored_filename' => $registro->stored_filename,
            'uploaded_at' => $registro->uploaded_at
        ];
        file_put_contents(self::$storage, json_encode($arr, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    public static function delete($id) {
        if (!file_exists(self::$storage)) return false;
        $arr = json_decode(file_get_contents(self::$storage), true) ?: [];
        $deleted = false;
        foreach ($arr as $k => $item) {
            if ($item['id'] == $id) {
                // remove file
                $fp = __DIR__ . '/../../uploads/' . ($item['stored_filename'] ?? '');
                if (!empty($item['stored_filename']) && file_exists($fp)) {
                    @unlink($fp);
                }
                unset($arr[$k]);
                $deleted = true;
                break;
            }
        }
        if ($deleted) {
            $arr = array_values($arr);
            file_put_contents(self::$storage, json_encode($arr, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        }
        return $deleted;
    }

    public static function nextId() {
        $all = self::all();
        $max = 0;
        foreach ($all as $r) {
            if (!empty($r->id) && $r->id > $max) $max = $r->id;
        }
        return $max + 1;
    }
}
