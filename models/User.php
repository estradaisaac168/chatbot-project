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
            $query = "INSERT INTO users (carnet, password, fullname, email) 
                        VALUES (:carnet, :password, :fullname, :email)";
            $this->db->query($query);
            $this->db->bind(':carnet', $user['carnet']);
            $this->db->bind(':password', $user['password']);
            $this->db->bind(':fullname', $user['fullname']);
            $this->db->bind(':email', $user['email']);
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
