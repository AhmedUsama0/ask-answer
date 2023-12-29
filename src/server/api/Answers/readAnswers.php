<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=utf-8");
header("Access-Control-Allow-Methods: POST");
header('Access-Control-Allow-Headers: Content-Type,Access-Control-Allow-Origin,Access-Control-Allow-Methods');

include_once __DIR__ . '/../../models/Answers.php';


if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") die();

$answer = new Answer();

$data = json_decode(file_get_contents("php://input"));

$answer->setQuestionId($data->question_id);

$stmt = $answer->readAnswers();

if ($stmt->rowCount() === 0) {

    echo json_encode(
        array(
            "error" => "This Question Has No Answers"
        )
    );

    die();
}

$Answers = array();

while ($answers = $stmt->fetch(PDO::FETCH_ASSOC)) {

    array_push($Answers, $answers);
}

echo json_encode($Answers);
