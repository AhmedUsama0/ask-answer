<?php

include_once __DIR__ . '/../config/database.php';

class Users extends Database

{
    protected function setUser($username, $email, $password): void
    {
        $db = $this->connect();
        $query = "INSERT INTO users (username,email,password) VALUES (?,?,?)";

        $stmt = $db->prepare($query);

        $stmt->execute([
            $username,
            $email,
            $password
        ]);
        $query = "INSERT INTO images (image,user_id) VALUES (?,?)";
        $stmt = $db->prepare($query);
        $stmt->execute([
            "male.png",
            $db->lastInsertId(),
        ]);
    }


    protected function getUser($email, $password)
    {
        $query = "SELECT users.user_id,users.email,users.password,images.image,users.username FROM users
        LEFT JOIN images ON users.user_id = images.user_id WHERE email = ? AND password=? ORDER BY images.image_id DESC LIMIT 1 ";
        $stmt = $this->connect()->prepare($query);
        $stmt->execute([
            $email,
            $password,
        ]);

        return $stmt;
    }


    protected function getAllUsers()
    {
        $query = "SELECT * FROM users";
        $stmt = $this->connect()->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    protected function fetchUserProfileData($user_id)
    {
        $query = "SELECT username,image FROM users
        INNER JOIN images ON users.user_id = images.user_id WHERE users.user_id=?";
        $stmt = $this->connect()->prepare($query);
        $stmt->execute([
            $user_id
        ]);
        return $stmt;
    }
}
