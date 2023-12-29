<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=utf-8");
header("Access-Control-Allow-Methods: POST");
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Origin,Access-Control-Allow-Methods');

include_once __DIR__ . '/../../models/Messages.php';


if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") die();

$message = new Message();

$data = json_decode(file_get_contents("php://input"));

$message->setReceiver($data->receiver);

$stmt = $message->getNumberOfUnReadMessages();

$numberOfUnReadMessages = $stmt->fetch(PDO::FETCH_ASSOC)['unread_messages'];

echo json_encode(array("numberOfUnReadMessages" => $numberOfUnReadMessages));
