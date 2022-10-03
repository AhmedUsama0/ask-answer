<?php
include_once __DIR__ . '/../config/database.php';

class Images extends Database
{
    protected function checkExistImage($user_id)
    {
        $query = "SELECT user_id FROM images WHERE user_id=?";
        $stmt = $this->connect()->prepare($query);
        $stmt->execute([
            $user_id,
        ]);
        return $stmt->rowCount();
    }
    protected function updateImage($image, $user_id)
    {
        $query = "UPDATE images SET image=? WHERE user_id=?";
        $stmt = $this->connect()->prepare($query);
        $stmt->execute([
            $image,
            $user_id,
        ]);
    }

    protected function setImage($image, $user_id)
    {
        $query = "INSERT INTO images (image,user_id) VALUES (?,?)";

        $stmt = $this->connect()->prepare($query);

        $stmt->execute([
            $image,
            $user_id,
        ]);
    }
}
