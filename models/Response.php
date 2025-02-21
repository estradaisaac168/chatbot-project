<?php

namespace Model;

use Core\Model;
use PDO;

class Response extends Model
{

    private $db;

    public function __construct()
    {
        $this->db = new Model();
    }


    public function getById($id)
    {
        try {
            $query = "SELECT * FROM responses WHERE parent_response = :parent_response";
            $this->db->query($query);
            $this->db->bind(':parent_response', $id);
            $row = $this->db->single();
            return $row ? $row : false;

        } catch (\PDOException $e) {
            echo $this->$e;
            return false;
        }
    }



    public function getAllById($id)
    {

        try {

            $query = "SELECT * FROM responses WHERE question_id = :id ORDER BY id";
            $this->db->query($query);
            $this->db->bind(':id', $id);
            $row = $this->db->resultSet();
            return ($row > 0) ? $row : false;

        } catch (\PDOException $e) {
            echo $this->$e;
            return false;
        }
    }
}
