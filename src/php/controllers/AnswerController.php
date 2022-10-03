<?php
include_once __DIR__ . '/../models/Answers.php';

class AnswerController extends Answers
{
    public function createAnswer()
    {
        $this->setAnswer();
    }

    public function getAnswersWithQuestionId()
    {
        $arr = [];
        $stmt = $this->fetchAnswersWithQuestionId();

        while ($answers = $stmt->fetch(PDO::FETCH_ASSOC)) {

            extract($answers);
            if ($image === null) {
                $image = "male.png";
            }
            $user = [
                "answer_id" => $answer_id,
                "answer" => $answer,
                "username" => $username,
                "userImage" => $image,
                "upvote" => $upvote,
                "user_id" => $user_id,
            ];
            array_push($arr, $user);
        }

        echo json_encode($arr);
    }



    public function destroyAnswer()
    {
        $this->deleteAnswer($_POST['answer_id']);
    }
}
