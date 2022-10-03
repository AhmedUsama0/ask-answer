<?php
include_once __DIR__ . '/../config/database.php';

class Messages extends Database
{

    protected function setMessage($message, $from, $to)
    {
        $query = "INSERT INTO messages (message,fromUser,toUser) VALUES (?,?,?)";
        $stmt = $this->connect()->prepare($query);
        $stmt->execute([
            $message,
            $from,
            $to,
        ]);
    }


    protected function fetchMessages($from, $to)
    {
        $query = "SELECT message,fromUser,toUser FROM messages 
        WHERE (fromUser='$from' AND toUser='$to') OR 
        (toUser='$from' AND fromUser='$to') ORDER BY message_id DESC";
        $stmt = $this->connect()->prepare($query);
        $stmt->execute();
        return $stmt;
    }
}
