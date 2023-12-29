<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=utf-8");
header("Access-Control-Allow-Methods: POST");
header('Access-Control-Allow-Headers: Content-Type,Access-Control-Allow-Origin,Access-Control-Allow-Methods');

include_once __DIR__ . '/../../models/Users.php';
include_once __DIR__ . '/../../filters/filter.php';

if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") die();

$data = json_decode(file_get_contents("php://input"));

$user_id = $data->user_id;
$email = Filter::filterEmail($data->email);
$username = Filter::filterUserName($data->username);
$about = Filter::filterText($data->about);
$password = Filter::hashPassword($data->password);
$date = $data->date;


$user = new User();

$user->setUserId($user_id);
$user->setEmail($email);
$user->setUserName($username);
$user->setAbout($about);
$user->setPassword($password);
$user->setDate($date);

$result = $user->updateUserInfo();

if (!$result) {
    echo json_encode(array("error" => "true"));
    die();
}

echo json_encode(array(
    "username" => $user->getUserName(),
    "email" => $user->getEmail(),
    "about" => $user->getAbout(),
    "password" => $data->password,
    "date" => $user->getDate(),
));
