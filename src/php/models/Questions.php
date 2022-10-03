<?php
include_once __DIR__ . '/../config/database.php';

class Questions extends Database
{

    protected function fetchQuestionsWithUserId($user_id)
    {
        $query = "SELECT question,question_id FROM questions WHERE user_id=$user_id ORDER BY question_id DESC";
        $stmt = $this->connect()->prepare($query);
        $stmt->execute();
        return $stmt;
    }


    protected function fetchAllQuestions()
    {
        $query = "SELECT * FROM questions";
        $stmt = $this->connect()->prepare($query);
        $stmt->execute();

        return $stmt;
    }

    protected function fetchOneQuestion($question_id)
    {
        $query = "SELECT questions.user_id, question,username,image FROM questions
        INNER JOIN users ON questions.user_id = users.user_id INNER JOIN
        images ON users.user_id = images.user_id WHERE
        question_id = ? ORDER BY image_id DESC LIMIT 1;";
        $stmt = $this->connect()->prepare($query);
        $stmt->execute([
            $question_id,
        ]);
        
        return $stmt;
    }

    protected function setQuestion($question, $user_id)
    {
        $query = "INSERT INTO questions (question,user_id) VALUES (?,?)";
        $stmt = $this->connect()->prepare($query);
        $stmt->execute([
            $question,
            $user_id,
        ]);
    }

    protected function deleteQuestion($question_id)
    {
        $query = "DELETE FROM questions WHERE question_id=?";
        $stmt = $this->connect()->prepare($query);
        $stmt->execute([
            $question_id,
        ]);
    }
}
