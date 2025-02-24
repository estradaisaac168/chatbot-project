<?php

namespace Model;

use Core\Model;
use PDO;

class Response extends Model
{

    private $db;
    private $id;
    private $response_text;
    private $question_id;
    private $parent_response;
    private $next_question;
    private $type_response;
    private $next_response;
    private $type_document;

    public function __construct()
    {
        $this->db = new Model();
    }


    public function getOne()
    {
        try {
            $query = "SELECT * FROM responses WHERE parent_response_id = :parent_response_id";
            $this->db->query($query);
            $this->db->bind(':parent_response_id', $this->getId());
            $row = $this->db->single();
            return $row ? $row : false;
        } catch (\PDOException $e) {
            echo $this->$e;
            return false;
        }
    }



    public function getAllById()
    {

        try {

            $query = "SELECT * FROM responses WHERE questions_id = :id ORDER BY id";
            $this->db->query($query);
            $this->db->bind(':id', $this->getQuestionId());
            $row = $this->db->resultSet();
            return ($row > 0) ? $row : false;
        } catch (\PDOException $e) {
            echo $this->$e;
            return false;
        }
    }


    // Getter y Setter para id
    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    // Getter y Setter para response_text
    public function getResponseText()
    {
        return $this->response_text;
    }

    public function setResponseText($response_text)
    {
        $this->response_text = $response_text;
    }

    // Getter y Setter para question_id
    public function getQuestionId()
    {
        return $this->question_id;
    }

    public function setQuestionId($question_id)
    {
        $this->question_id = $question_id;
    }

    // Getter y Setter para parent_response
    public function getParentResponse()
    {
        return $this->parent_response;
    }

    public function setParentResponse($parent_response)
    {
        $this->parent_response = $parent_response;
    }

    // Getter y Setter para next_question
    public function getNextQuestion()
    {
        return $this->next_question;
    }

    public function setNextQuestion($next_question)
    {
        $this->next_question = $next_question;
    }

    // Getter y Setter para type_response
    public function getTypeResponse()
    {
        return $this->type_response;
    }

    public function setTypeResponse($type_response)
    {
        $this->type_response = $type_response;
    }

    // Getter y Setter para next_response
    public function getNextResponse()
    {
        return $this->next_response;
    }

    public function setNextResponse($next_response)
    {
        $this->next_response = $next_response;
    }

    // Getter y Setter para type_document
    public function getTypeDocument()
    {
        return $this->type_document;
    }

    public function setTypeDocument($type_document)
    {
        $this->type_document = $type_document;
    }
}
