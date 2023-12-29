<?php
include_once(__DIR__ . "/../config/database.php");

class Question extends Database
{
    private object $conn;

    private int $question_id;
    private string $question;
    private int $user_id;

    public function __construct()
    {
        $this->conn = $this->connect();
    }


    public function setQuestionId(int $question_id): void
    {
        $this->question_id = $question_id;
    }

    public function setQuestion(string $question): void
    {
        $this->question = $question;
    }

    public function setUserId(int $user_id): void
    {
        $this->user_id = $user_id;
    }

    public function readUserQuestions(): object
    {
        $query = "SELECT question,question_id FROM questions WHERE user_id=$this->user_id ORDER BY question_id DESC";

        $stmt = $this->conn->prepare($query);

        $stmt->execute();

        return $stmt;
    }


    public function readAllQuestions(): object
    {
        $query = "SELECT users.user_id,question,questions.question_id,image,username FROM questions INNER JOIN users INNER JOIN images
         WHERE questions.user_id = users.user_id AND users.user_id = images.user_id;";

        $stmt = $this->conn->prepare($query);

        $stmt->execute();

        return $stmt;
    }

    public function readOneQuestion(): object
    {
        $query = "SELECT questions.user_id, question,username,image FROM questions
        INNER JOIN users ON questions.user_id = users.user_id INNER JOIN
        images ON users.user_id = images.user_id WHERE
        question_id = ? ORDER BY image_id DESC LIMIT 1;";

        $stmt = $this->conn->prepare($query);

        $stmt->execute([
            $this->question_id,
        ]);

        return $stmt;
    }

    public function createQuestion(): void
    {
        $query = "INSERT INTO questions (question,user_id) VALUES (?,?)";

        $stmt = $this->conn->prepare($query);

        $stmt->execute([
            $this->question,
            $this->user_id,
        ]);
    }

    public function deleteQuestion(): void
    {
        $query = "DELETE FROM questions WHERE question_id=?";

        $stmt = $this->conn->prepare($query);

        $stmt->execute([
            $this->question_id,
        ]);
    }
}
