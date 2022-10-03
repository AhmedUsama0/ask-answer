<?php 
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Headers: Content-Type');
include_once __DIR__ . '/controllers/MessageController.php';
$MessageController =  new MessageController();
$MessageController->createMessage();