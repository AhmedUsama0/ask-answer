<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=utf-8");
header("Access-Control-Allow-Methods: POST");
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Origin,Access-Control-Allow-Methods');

include_once __DIR__ . '/../../models/Questions.php';

if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") die();


$question = new Question();

$data = json_decode(file_get_contents("php://input"));

$question->setQuestionId($data->question_id);

$question->deleteQuestion();
