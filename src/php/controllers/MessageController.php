<?php
include_once __DIR__ . '/../models/Messages.php';

class MessageController extends Messages
{
    public function createMessage()
    {
        $this->setMessage($_POST['message'], $_POST['from'], $_POST['to']);
    }

    public function getMessages()
    {
        $stmt = $this->fetchMessages($_POST['from'], $_POST['to']);
        $messages = [];
        while ($message = $stmt->fetch(PDO::FETCH_ASSOC)) {
            extract($message);
            $msg = [
                "message" => $message,
                "from" => $fromUser,
                "to" => $toUser,
            ];

            array_push($messages, $msg);
        }

        echo json_encode($messages);
    }
}
