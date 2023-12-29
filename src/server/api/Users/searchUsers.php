<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=utf-8");
header("Access-Control-Allow-Methods: POST");
header('Access-Control-Allow-Headers: Content-Type,Access-Control-Allow-Origin,Access-Control-Allow-Methods');

include_once __DIR__ . '/../../models/Users.php';

if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") die();

$user = new User();

$data = json_decode(file_get_contents("php://input"));

if (empty(trim($data->pattern))) die();

$stmt = $user->searchUsers(ucfirst($data->pattern));

$result = array();

while ($users = $stmt->fetch(PDO::FETCH_ASSOC)) {
    array_push($result, $users);
}

echo json_encode($result);
