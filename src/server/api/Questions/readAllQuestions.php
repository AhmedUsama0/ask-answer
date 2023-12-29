<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=utf-8");
header("Access-Control-Allow-Methods: POST");
header('Access-Control-Allow-Headers: Content-Type,Access-Control-Allow-Origin,Access-Control-Allow-Methods');

include_once __DIR__ . '/../../models/Questions.php';

if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") die();


$Question = new Question();

$stmt = $Question->readAllQuestions();

if ($stmt->rowCount() === 0) {

    echo json_encode(array("error" => "No Questions."));

    die();
}

$questions = array();

while ($question = $stmt->fetch(PDO::FETCH_ASSOC)) {

    array_push($questions, $question);
}


echo json_encode($questions);
