<?php
include_once __DIR__ . '/../config/database.php';

class Answers extends Database
{
    protected function setAnswer()
    {
        $query = "INSERT INTO answers (answer,question_id,user_id) VALUES (?,?,?)";
        $stmt = $this->connect()->prepare($query);
        $stmt->execute([
            $_POST['answer'],
            $_POST['question_id'],
            $_POST['user_id'],
        ]);
    }

    protected function fetchAnswersWithQuestionId()
    {
        //we select all answers and users records
        $query = "SELECT answer,answers.user_id,answers.answer_id,username,image
        FROM answers LEFT JOIN users ON answers.user_id = users.user_id
        LEFT JOIN images ON users.user_id = images.user_id  WHERE question_id=? GROUP BY answers.answer_id";
        $stmt = $this->connect()->prepare($query);
        $stmt->execute([
            $_GET['question_id']
        ]);
        return $stmt;
    }


    protected function deleteAnswer($answer_id)
    {
        $query = "DELETE FROM answers WHERE answer_id=?";
        $stmt = $this->connect()->prepare($query);
        $stmt->execute([
            $answer_id,
        ]);
    }
}
