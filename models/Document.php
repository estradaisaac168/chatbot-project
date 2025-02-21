<?php

namespace Model;

use Core\Model;
use PDO;

class Document extends Model
{

    private $db;

    public function __construct()
    {
        $this->db = new Model();
    }

    public function save($document)
    {

        try {

            $query = "INSERT INTO documents (document_name, file_path, created_at, response_id) 
            VALUES (:document_name, :file_path, :created_at, :response_id)";
            $this->db->query($query);
            $this->db->bind(':document_name', $document['name']);
            $this->db->bind(':file_path', $document['path']);
            $this->db->bind(':created_at', $document['createdAt']);
            $this->db->bind(':response_id', $document['responseId']);

            return ($this->db->execute()) ? $this->db->lastId() : false;

        } catch (\PDOException $e) {
            echo $this->$e;
            return false;
        }
    }

    public function getById($id)
    {
        try {
            $query = "SELECT d.id, d.document_name, d.file_path, d.created_at, d.response_id, r.response_text, t.name
                        FROM documents d
                        INNER JOIN responses r 
                        ON d.response_id = r.id
                        INNER JOIN types_documents t  
                        ON r.type_response = t.id
                        WHERE d.id = :id";
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
