<?php

namespace Model;

use Core\Model;
use PDO;

class Document extends Model
{

    private $db;
    private $id;
    private $name;
    private $absolute_path;
    private $relative_path;
    private $created_at;
    private $responses_id;

    public function __construct()
    {
        $this->db = new Model();
    }

    public function save()
    {

        try {

            $query = "INSERT INTO documents (name, absolute_path, relative_path, responses_id) 
            VALUES (:name, :absolute_path, :relative_path, :responses_id)";
            $this->db->query($query);
            $this->db->bind(':name', $this->getName());
            $this->db->bind(':absolute_path', $this->getAbsolutePath());
            $this->db->bind(':relative_path', $this->getRelativePath());
            $this->db->bind(':responses_id', $this->getResponsesId());

            return ($this->db->execute()) ? $this->db->lastId() : false;

        } catch (\PDOException $e) {
            echo $this->$e;
            return false;
        }
    }

    public function getById()
    {
        try {
            $query = "SELECT d.id, d.name, d.absolute_path, d.relative_path, d.created_at, d.responses_id, r.response_text, t.type
                        FROM documents d
                        INNER JOIN responses r 
                        ON d.responses_id = r.id
                        INNER JOIN document_types t  
                        ON r.document_types_id = t.id
                        WHERE d.id = :id";
            $this->db->query($query);
            $this->db->bind(':id', $this->getId());
            $row = $this->db->single();
            return $row ? $row : false;

        } catch (\PDOException $e) {
            echo $this->$e;
            return false;
        }
    }


    public function getId() { return $this->id; }
    public function setId($id) { $this->id = $id; }

    public function getName() { return $this->name; }
    public function setName($name) { $this->name = $name; }

    public function getAbsolutePath() { return $this->absolute_path; }
    public function setAbsolutePath($absolute_path) { $this->absolute_path = $absolute_path; }

    public function getRelativePath() { return $this->relative_path; }
    public function setRelativePath($relative_path) { $this->relative_path = $relative_path; }

    public function getCreatedAt() { return $this->created_at; }
    public function setCreatedAt($created_at) { $this->created_at = $created_at; }

    public function getResponsesId() { return $this->responses_id; }
    public function setResponsesId($responses_id) { $this->responses_id = $responses_id; }

}
