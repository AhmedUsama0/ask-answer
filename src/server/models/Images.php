<?php
include_once(__DIR__ . "/../config/database.php");

class Image extends Database
{
    private object $conn;

    private string $image;
    private int $user_id;


    public function __construct()
    {
        $this->conn = $this->connect();
    }


    public function setImage(string $image): void
    {
        $this->image = $image;
    }

    public function setUserId(int $user_id): void
    {
        $this->user_id = $user_id;
    }

    public function updateImage(): void
    {
        $query = "UPDATE images SET image=? WHERE user_id=?";

        $stmt = $this->conn->prepare($query);

        $stmt->execute([
            $this->image,
            $this->user_id,
        ]);
    }

    public function createImage(): void
    {
        $query = "INSERT INTO images (user_id) VALUES (?)";

        $stmt = $this->conn->prepare($query);

        $stmt->execute([
            $this->user_id,
        ]);
    }
}
