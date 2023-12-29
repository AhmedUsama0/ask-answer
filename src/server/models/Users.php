<?php
include_once(__DIR__ . "/../config/database.php");

class User extends Database
{
    private object $conn;

    private int $user_id;
    private string $username;
    private string $email;
    private string $password;
    private $date;
    private string $about;


    public function __construct()
    {
        $this->conn = $this->connect();
    }

    public function setUserId(int $user_id): void
    {
        $this->user_id = $user_id;
    }

    public function getUserId(): int
    {
        return $this->user_id;
    }



    public function setUserName(string $username): void
    {
        $this->username = $username;
    }

    public function getUserName(): string
    {
        return $this->username;
    }



    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getEmail(): string
    {
        return $this->email;
    }




    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setDate($date): void
    {
        $this->date = $date;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function setAbout(string $about)
    {
        $this->about = !empty(trim($about)) ? $about : "There is no info to show.";
    }

    public function getAbout(): string
    {
        return $this->about;
    }


    public function createUser(): void
    {
        $query = "INSERT INTO users (username,email,password,date,about) VALUES (?,?,?,?,?)";

        $stmt = $this->conn->prepare($query);

        $stmt->execute([
            $this->username,
            $this->email,
            $this->password,
            $this->date,
            $this->about,
        ]);

        $this->setUserId($this->conn->lastInsertId());
    }


    public function readUser(): object
    {
        $query = "SELECT users.user_id,users.email,users.password,images.image,users.username,users.date,users.about FROM users
        LEFT JOIN images ON users.user_id = images.user_id WHERE email = ? ORDER BY images.image_id DESC LIMIT 1 ";

        $stmt = $this->conn->prepare($query);

        $stmt->execute([
            $this->email,
        ]);

        return $stmt;
    }


    public function readMatchedUsers(): object
    {
        $query = "SELECT username,email  FROM users WHERE email = ? OR username = ?";

        $stmt = $this->conn->prepare($query);

        $stmt->execute([
            $this->email,
            $this->username
        ]);

        return $stmt;
    }

    public function readUserData(): object
    {
        $query = "SELECT username,image,date,about FROM users
        INNER JOIN images ON users.user_id = images.user_id WHERE users.user_id=?";

        $stmt = $this->conn->prepare($query);

        $stmt->execute([
            $this->user_id
        ]);

        return $stmt;
    }

    public function searchUsers($pattern): object
    {
        $query = "SELECT username,users.user_id,image FROM users INNER JOIN 
        images WHERE username LIKE ? AND users.user_id = images.user_id";

        $stmt = $this->conn->prepare($query);

        $stmt->execute([
            "$pattern%"
        ]);

        return $stmt;
    }

    public function updateUserInfo(): bool
    {
        $query = "UPDATE users SET username = ?,email = ?, password = ?, date = ?, about = ? WHERE user_id = ?";
        $stmt = $this->conn->prepare($query);
        $result =  $stmt->execute([
            $this->username,
            $this->email,
            $this->password,
            $this->date,
            $this->about,
            $this->user_id,
        ]);

        return $result;
    }
}
