<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=utf-8");
header("Access-Control-Allow-Methods: POST");
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Origin,Access-Control-Allow-Methods');

include_once __DIR__ . '/../../models/Questions.php';
include_once __DIR__ . '/../../filters/filter.php';


if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") die();


$question = new Question();

$data = json_decode(file_get_contents("php://input"));

if (empty(trim($data->question))) die();

$question->setQuestion($data->question);
$question->setUserId($data->user_id);


$question->createQuestion();
