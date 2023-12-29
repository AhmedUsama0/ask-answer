<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=utf-8");
header("Access-Control-Allow-Methods: POST");
header('Access-Control-Allow-Headers: Content-Type,Access-Control-Allow-Origin,Access-Control-Allow-Methods');

include_once __DIR__ . '/../../models/Users.php';
include_once __DIR__ . '/../../filters/filter.php';

if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") die();

$user = new User();

$data = json_decode(file_get_contents("php://input"));

$user->setUserId($data->user_id);

$stmt = $user->readUserData();

if ($stmt->rowCount() > 0) {

    $userData = $stmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode($userData);

    die();
}

echo json_encode(
    array("error" => "User Doesn't Exist.")
);
