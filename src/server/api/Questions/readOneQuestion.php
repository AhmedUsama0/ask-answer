<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=utf-8");
header("Access-Control-Allow-Methods: POST");
header('Access-Control-Allow-Headers: Content-Type,Access-Control-Allow-Origin,Access-Control-Allow-Methods');

include_once __DIR__ . '/../../models/Questions.php';

if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") die();


$Question = new Question();

$data = json_decode(file_get_contents("php://input"));

$Question->setQuestionId($data->question_id);

$stmt = $Question->readOneQuestion();

if ($stmt->rowCount() === 0) {

    echo json_encode(
        array("error" => "The question does not exist.")
    );

    die();
}

$userQuestion = $stmt->fetch(PDO::FETCH_ASSOC);

echo json_encode($userQuestion);
