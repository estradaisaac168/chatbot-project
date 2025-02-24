<?php

namespace Model;

use Core\Model;
use PDO;

class User extends Model
{

    private $db;
    private $id;
    private $name;
    private $lastname;
    private $email;
    private $password;
    private $carne;
    private $created_at;
    private $login;

    public function __construct()
    {
        $this->db = new Model();
    }


    public function save()
    {
        try {
            $query = "INSERT INTO users ( name, lastname, email, password, carne,login) 
                        VALUES (:name, :lastname, :email, :password, :carne, :login)";
            $this->db->query($query);
            $this->db->bind(':name', $this->getName());
            $this->db->bind(':lastname', $this->getLastname());
            $this->db->bind(':email', $this->getEmail());
            $this->db->bind(':password', $this->getPassword());
            $this->db->bind(':carne', $this->getCarne());
            $this->db->bind(':login', $this->getLogin());
            return $this->db->execute() ? true : false;
        } catch (\PDOException $e) {
            echo $this->$e;
            return false;
        }
    }


    public function login()
    {
        try {
            $query = "SELECT *
                        FROM users 
                        WHERE carne = :carne";
            $this->db->query($query);
            $this->db->bind(':carne', $this->getCarne());
            $row = $this->db->single();

            if ($row) {
                $hashedPassword = $row->password;

                return (password_verify($this->getPassword(), $hashedPassword)) ? $row : false;
            }
        } catch (\PDOException $e) {
            echo $this->$e;
            return false;
        }
    }



    // Getters
    public function getId()
    {
        return $this->id;
    }
    public function getName()
    {
        return $this->name;
    }
    public function getLastname()
    {
        return $this->lastname;
    }
    public function getEmail()
    {
        return $this->email;
    }
    public function getPassword()
    {
        return $this->password;
    }
    public function getCarne()
    {
        return $this->carne;
    }
    public function getCreatedAt()
    {
        return $this->created_at;
    }
    public function getLogin()
    {
        return $this->login;
    }

    // Setters
    public function setId($id)
    {
        $this->id = $id;
    }
    public function setName($name)
    {
        $this->name = $name;
    }
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
    }
    public function setEmail($email)
    {
        $this->email = $email;
    }
    public function setPassword($password)
    {
        $this->password = $password;
    }
    public function setCarne($carne)
    {
        $this->carne = $carne;
    }
    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
    }
    public function setLogin($login)
    {
        $this->login = $login;
    }
}
