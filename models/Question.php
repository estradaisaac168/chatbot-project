<?php

namespace Model;

use Core\Model;
use PDO;

class Question extends Model
{

    private $db;

    public function __construct()
    {
        $this->db = new Model();
    }

    public function getById($id)
    {

        try {

            $query = "SELECT * FROM questions WHERE id = :id ORDER BY id";
            $this->db->query($query);
            $this->db->bind(':id', $id);
            $row = $this->db->single();
            return $row ? $row : false;

        } catch (\PDOException $e) {
            echo $this->$e;
            return false;
        }
    }
}
