<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=utf-8");
header("Access-Control-Allow-Methods: POST");
header('Access-Control-Allow-Headers: Content-Type,Access-Control-Allow-Origin,Access-Control-Allow-Methods');

include_once __DIR__ . '/../../models/Images.php';

if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") die();


$image = new Image();

if ($_FILES["img"]["size"] !== 0 && $_FILES["img"]["error"] === 0 && isset($_POST["user_id"]) && !empty($_POST["user_id"])) {

    //$_FILES['img']['name'] works without basename
    $target_file = __DIR__ . "/../../uploads/" . basename($_FILES['img']['name']);

    //works without strtolower but we use strtolower just to make sure the extension will be small not capital
    $fileExtension = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    $allowedExtensions = array("jpg", "jpeg", "png");

    if (in_array($fileExtension, $allowedExtensions)) {

        move_uploaded_file($_FILES['img']['tmp_name'], $target_file);

        $image->setImage(basename($_FILES["img"]["name"]));
        $image->setUserId($_POST["user_id"]);

        $image->updateImage();

        echo json_encode(
            array("userImage" => basename($_FILES["img"]["name"]))
        );

        return;
    }

    echo json_encode(
        array("error" => "This file is not an image. Please provide image in jpg,jpeg or png extension.")
    );
}
