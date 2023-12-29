<?php
include_once(__DIR__ . "/../config/database.php");

class Answer extends Database
{
    private object $conn;

    private int $answer_id;
    private string $answer;
    private int $question_id;
    private int $user_id;


    public function __construct()
    {
        $this->conn = $this->connect();
    }

    public function setAnswerId(int $answer_id): void
    {
        $this->answer_id = $answer_id;
    }

    public function setAnswer(string $answer): void
    {
        $this->answer = $answer;
    }

    public function setQuestionId(int $question_id): void
    {
        $this->question_id = $question_id;
    }

    public function setUserId(int $user_id): void
    {
        $this->user_id = $user_id;
    }

    public function createAnswer(): void
    {
        $query = "INSERT INTO answers (answer,question_id,user_id) VALUES (?,?,?)";

        $stmt = $this->conn->prepare($query);

        $stmt->execute([
            $this->answer,
            $this->question_id,
            $this->user_id,
        ]);
    }

    public function readAnswers(): object
    {
        //we select all answers and users records
        $query = "SELECT answer,answers.user_id,answers.answer_id,username,image
        FROM answers LEFT JOIN users ON answers.user_id = users.user_id
        LEFT JOIN images ON users.user_id = images.user_id  WHERE question_id=? GROUP BY answers.answer_id";

        $stmt = $this->conn->prepare($query);

        $stmt->execute([
            $this->question_id
        ]);

        return $stmt;
    }


    public function deleteAnswer(): void
    {
        $query = "DELETE FROM answers WHERE answer_id=?";

        $stmt = $this->conn->prepare($query);

        $stmt->execute([
            $this->answer_id,
        ]);
    }
}
