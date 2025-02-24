<?php

namespace Model;

use Core\Model;
use PDO;

class Question extends Model
{

    private $db;
    private $id;
    private $question_text;

    public function __construct()
    {
        $this->db = new Model();
    }

    public function getOne()
    {

        try {

            $query = "SELECT * FROM questions WHERE id = :id ORDER BY id";
            $this->db->query($query);
            $this->db->bind(':id', $this->getId());
            $row = $this->db->single();
            return $row ? $row : false;

        } catch (\PDOException $e) {
            echo $this->$e;
            return false;
        }
    }

    // Getter y Setter para id
    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    // Getter y Setter para question_text
    public function getQuestionText() {
        return $this->question_text;
    }

    public function setQuestionText($question_text) {
        $this->question_text = $question_text;
    }

}
