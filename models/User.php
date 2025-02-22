<?php

namespace Model;

use Core\Model;
use PDO;

class User extends Model
{

    private $db;

    public function __construct()
    {
        $this->db = new Model();
    }


    public function save($user)
    {
        try {
            $query = "INSERT INTO users (carnet, password) 
                        VALUES (:carnet, :password)";
            $this->db->query($query);
            $this->db->bind(':carnet', $user['carnet']);
            $this->db->bind(':password', $user['password']);
            return $this->db->execute() ? true : false;
        } catch (\PDOException $e) {
            echo $this->$e;
            return false;
        }
    }


    public function login($carnet, $password)
    {
        try {
            $query = "SELECT *
                        FROM users 
                        WHERE carnet = :carnet";
            $this->db->query($query);
            $this->db->bind(':carnet', $carnet);
            $row = $this->db->single();
            $hashedPassword = $row->password;
            return (password_verify($password, $hashedPassword)) ? $row : false;
        } catch (\PDOException $e) {
            echo $this->$e;
            return false;
        }
    }
}
