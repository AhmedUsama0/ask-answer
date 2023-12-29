<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=utf-8");
header("Access-Control-Allow-Methods: POST");
header('Access-Control-Allow-Headers: Content-Type,Access-Control-Allow-Origin,Access-Control-Allow-Methods');

include_once __DIR__ . '/../../models/Messages.php';



if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") die();

$message = new Message();

$data = json_decode(file_get_contents("php://input"));


$message->setSender($data->sender);
$message->setReceiver($data->receiver);



$stmt = $message->readMessages();

if ($stmt->rowCount()  === 0) {

    echo json_encode(
        array("error" => "No Messages.")
    );

    die();
}


$messages = array();

while ($message = $stmt->fetch(PDO::FETCH_ASSOC)) {

    array_push($messages, $message);
}

echo json_encode($messages);
