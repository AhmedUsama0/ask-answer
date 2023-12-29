<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=utf-8");
header("Access-Control-Allow-Methods: POST");
header('Access-Control-Allow-Headers: Content-Type,Access-Control-Allow-Origin,Access-Control-Allow-Methods');

include_once __DIR__ . '/../../models/Users.php';
include_once __DIR__ . '/../../filters/filter.php';

if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") die();

$data = json_decode(file_get_contents("php://input"));

if (empty(trim($data->email)) || empty($data->password)) {

    echo json_encode(array('error' => "It seems some fields are empty!. Please fill the fields and try again."));

    die();
}

$email = Filter::filterEmail($data->email);

$user = new User();
$user->setEmail($email);
$user->setPassword($data->password);

$stmt = $user->readUser();

if ($stmt->rowCount() === 0) {

    echo json_encode(array("accountError" => "account does not exist."));

    die();
}


$existUser = $stmt->fetch(PDO::FETCH_ASSOC);

extract($existUser);

if (Filter::verifyPassword($user->getPassword(), $password) === false) {

    echo json_encode(array("passwordError" => "password is incorrect."));

    die();
}

echo json_encode(
    array(
        'username' => $username,
        'password' => $user->getPassword(),
        'user_id' => $user_id,
        'userImage' => $image,
        'email' => $email,
        'date' => $date,
        'about' => $about,
    )
);
