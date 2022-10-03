<?php
include_once __DIR__ . '/../models/Questions.php';

class QuestionController extends Questions
{

    public function getQuestionsWithUserId()
    {
        $userQuestions = [];
        $stmt = $this->fetchQuestionsWithUserId($_POST['user_id']);
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            extract($row);

            $questions = [
                'question_id' => $question_id,
                'question' => $question,
            ];
            array_push($userQuestions, $questions);
        }

        echo json_encode($userQuestions);
    }


    public function getAllQuestions()
    {
        $stmt = $this->fetchAllQuestions();
        $allQuestions = [];
        while ($questions = $stmt->fetch(PDO::FETCH_ASSOC)) {
            extract($questions);
            $record = [
                'question_id' => $question_id,
                'user_id' => $user_id,
                'question' => $question,

            ];
            array_push($allQuestions, $record);
        }

        echo json_encode($allQuestions);
    }


    public function getOneQuestion()
    {
        $stmt = $this->fetchOneQuestion($_GET['question_id']);
        $question = $stmt->fetch(PDO::FETCH_ASSOC);
        extract($question);
        $result = [
            'question' => $question,
            'username' => $username,
            'userImage' => $image,
            'user_id' => $user_id,
        ];

        echo json_encode($result);
    }


    public function createQuestion()
    {
        $this->setQuestion($_POST['question'], $_POST['user_id']);
    }

    public function destroyQuestion()
    {
        $this->deleteQuestion($_POST['question_id']);
    }
}
