<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=utf-8");
header("Access-Control-Allow-Methods: POST");
header('Access-Control-Allow-Headers: Content-Type,Access-Control-Allow-Origin,Access-Control-Allow-Methods');

include_once __DIR__ . '/../../models/Users.php';
include_once __DIR__ . '/../../models/Images.php';
include_once __DIR__ . '/../../filters/filter.php';


if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") die();

$data = json_decode(file_get_contents("php://input"));

// First Stage
if (empty(trim($data->email)) || empty(trim($data->username))) {

    echo json_encode(array(
        'error' => "It seems some fields are empty!. Please fill the fields and try again."
    ));

    die();
}

$user = new User();

// Second Stage
if (!empty(trim($data->email)) && !empty(trim($data->username)) && !empty($data->password) && !empty($data->date)) {

    $email = Filter::filterEmail($data->email);
    $username = Filter::filterUserName($data->username);
    $password = Filter::hashPassword($data->password);


    $user->setUserName($username);
    $user->setEmail($email);
    $user->setPassword($password);
    $user->setDate($data->date);
    $user->setAbout($data->about);
    $user->createUser();


    $image = new Image();
    $image->setUserId($user->getUserId());
    $image->createImage();

    echo json_encode(array("success" => "true"));

    die();
}

// First Stage
if (!empty(trim($data->email)) && !empty(trim($data->username))) {

    $email = Filter::filterEmail($data->email);
    $username = Filter::filterUserName($data->username);

    $user->setUserName($username);
    $user->setEmail($email);

    $stmt = $user->readMatchedUsers();

    if ($stmt->rowCount() === 0) {

        echo json_encode(array("success" => "true"));

        die();
    }

    $error = array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

        extract($row);

        if ($email === $user->getEmail() && $username === $user->getUserName()) :

            $error["accountError"] = "account is already exist.";
            echo json_encode($error);
            die();

        endif;
        if ($email === $user->getEmail()) :

            $error["emailError"] = "email is already exist.";

        endif;

        if ($username === $user->getUserName()) :

            $error["userNameError"] = "username is already exist.";

        endif;
    }
}

echo json_encode($error);
