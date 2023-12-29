<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=utf-8");
header("Access-Control-Allow-Methods: POST");
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Origin,Access-Control-Allow-Methods');

include_once __DIR__ . '/../../models/Messages.php';


if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") die();

$message = new Message();

$data = json_decode(file_get_contents("php://input"));

$message->setSender($data->sender);
$message->setReceiver($data->receiver);

$message->updateMessageStatus();
