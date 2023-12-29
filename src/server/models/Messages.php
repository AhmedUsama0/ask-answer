<?php
include_once(__DIR__ . "/../config/database.php");

class Message extends Database
{
    private object $conn;

    private string $message;
    private int $sender;
    private int $receiver;


    private int $user_id;



    public function __construct()
    {
        $this->conn = $this->connect();
    }

    public function setMessage(string $message): void
    {
        $this->message = $message;
    }

    public function setSender(int $sender): void
    {
        $this->sender = $sender;
    }

    public function setReceiver(int $receiver): void
    {
        $this->receiver = $receiver;
    }


    public function setUserId(int $user_id): void
    {
        $this->user_id = $user_id;
    }

    public function createMessage(): void
    {
        $query = "INSERT INTO messages (message,sender,receiver) VALUES (?,?,?)";

        $stmt = $this->conn->prepare($query);

        $stmt->execute([
            $this->message,
            $this->sender,
            $this->receiver,
        ]);
    }


    public function readMessages(): object
    {
        $query = "SELECT m.*,
        s.username as sender_name,
        r.username as receiver_name,
        si.image as sender_image,
        ri.image as receiver_image
        FROM messages m
        INNER JOIN users s ON m.sender = s.user_id
        INNER JOIN users r ON m.receiver = r.user_id 
        INNER JOIN images si ON m.sender = si.user_id
        INNER JOIN images ri ON m.receiver = ri.user_id
        WHERE (sender = :sender AND receiver = :receiver) OR (sender = :receiver AND receiver = :sender) ORDER BY message_id DESC";

        $stmt = $this->conn->prepare($query);

        $stmt->execute([
            "sender" => $this->sender,
            "receiver" => $this->receiver
        ]);

        return $stmt;
    }

    public function readConversations(): object
    {
        // $query = "SELECT users.user_id,username,image FROM 
        // messages INNER JOIN users ON users.user_id = messages.sender OR users.user_id = messages.receiver 
        // INNER JOIN images ON users.user_id = images.user_id WHERE users.user_id != ? AND (sender = ? OR receiver = ?) 
        // GROUP BY username ORDER BY message_id DESC;
        // ";
        $query = "SELECT m.*,s.username sender_name,r.username receiver_name,
        si.image sender_image,ri.image receiver_image 
        FROM (SELECT m.*, ROW_NUMBER() OVER (PARTITION BY LEAST(m.sender, m.receiver), 
        GREATEST(m.sender, m.receiver) ORDER BY m.message_id DESC) AS seqnum 
        FROM messages m ) m INNER JOIN users s ON s.user_id = m.sender 
        INNER JOIN users r ON r.user_id = m.receiver INNER JOIN images si 
        ON s.user_id = si.user_id INNER JOIN images ri ON r.user_id = ri.user_id 
        WHERE seqnum = 1 AND (sender = :user_id OR receiver = :user_id) ORDER BY timestamp DESC;
        ";
        $stmt = $this->conn->prepare($query);

        $stmt->execute([
            "user_id" => $this->user_id,
        ]);

        return $stmt;
    }


    public function updateMessageStatus(): void
    {
        $query = "UPDATE messages SET is_read = 1 WHERE sender = ? AND receiver = ? AND is_read = 0";

        $stmt = $this->conn->prepare($query);

        $stmt->execute([
            $this->sender,
            $this->receiver,
        ]);
    }

    public function getNumberOfUnReadMessages(): object
    {
        $query = "SELECT COUNT(is_read) as unread_messages FROM messages WHERE is_read = 0 AND receiver = ?";

        $stmt = $this->conn->prepare($query);

        $stmt->execute([
            $this->receiver,
        ]);

        return $stmt;
    }
}
