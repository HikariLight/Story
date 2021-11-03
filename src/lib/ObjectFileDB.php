<?php

class FileStore {

    private $file;
    private $fp;

    private function __construct($file) {
        $this->file = $file;
        $this->fp = null;
    }

    public function getFileName() {
        return $this->file;
    }

    public static function makeStore($file, $defaultContent) {
        $store = new FileStore($file);
        $store->initializeIfEmpty($defaultContent);
        return $store;
    }

    private function initializeIfEmpty($defaultContent) {
        $this->lockFile();
        if (file_exists($this->file)) {
            $content = file_get_contents($this->file);
            if ($content !== '') {
                $this->unlockFile();
                return false;
            }
        }
        $this->saveData($defaultContent);
        return true;
    }

    public function lockFile() {
        if ($this->fp !== null) {
            return;
        }
        $this->fp = fopen($this->file, 'ab+');
        if ($this->fp === false) {
            throw new Exception("Impossible d'ouvrir le fichier '{$this->file}' en écriture");
        }
    }

    public function unlockFile() {
        if ($this->fp === null) {
            return;
        }
        fclose($this->fp);
        $this->fp = null;
    }

    public function loadData() {
        $this->lockFile();
        $content = file_get_contents($this->file);
        $data = unserialize(base64_decode($content));
        if ($data === false) {
            throw new Exception("Could not unserialize data!");
        }
        return $data;
    }

    public function saveData($data) {
        $this->lockFile();
        if ($data === false) {
            throw new Exception("Cannot save constant FALSE");
        }
        $content = base64_encode(serialize($data));
        ftruncate($this->fp, 0);
        fwrite($this->fp, $content);
        $this->unlockFile();
    }

    public function __destruct() {
        $this->unlockFile();
    }
}

class ObjectFileDB {

    private $file_store;

    public function __construct($file) {
        $this->file_store = FileStore::makeStore($file, array());
    }

    static private function generate_id($db) {
        do {
            $id = bin2hex(openssl_random_pseudo_bytes(8));
        } while (is_numeric($id[0]) || key_exists($id, $db));

        return $id;
    }

    private function loadArray() {
        $data = $this->file_store->loadData();
        if ( ! is_array($data) ) {
            throw new Exception("File '".$this->file_store->getFileName()."' does not contain an array; maybe it was corrupted?");
        }
        return $data;
    }

    public function exists($id) {
        $db = $this->loadArray();
        $this->file_store->unlockFile();
        return key_exists($id, $db);
    }

    public function insert($obj) {
        $db = $this->loadArray();
        $id = self::generate_id($db);
        $db[$id] = $obj;
        $this->file_store->saveData($db);
        return $id;
    }

    public function fetch($id) {
        $db = $this->loadArray();
        $this->file_store->unlockFile();
        if ( ! key_exists($id, $db)) {
            throw new Exception("Key does not exist");
        }
        return $db[$id];
    }

    public function fetchAll() {
        $db = $this->loadArray();
        $this->file_store->unlockFile();
        return $db;
    }

    public function delete($id) {
        $db = $this->loadArray();
        if ( ! key_exists($id, $db)) {
            throw new Exception("Key does not exist");
        }
        unset($db[$id]);
        $this->file_store->saveData($db);
    }

    public function update($id, $obj) {
        $db = $this->loadArray();
        if ( ! key_exists($id, $db)) {
            throw new Exception("Key does not exist");
        }
        $db[$id] = $obj;
        $this->file_store->saveData($db);
    }

    public function deleteAll() {
        $this->file_store->saveData(array());
    }

}

?>