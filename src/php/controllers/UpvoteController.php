<?php
include_once __DIR__ . '/../models/Upvotes.php';

class UpvoteController extends Upvotes
{

    public function upvoteUserAnswer()
    {

        if ($this->checkUpvotedUser($_POST['user_id'], $_POST['answer_id']) === 0) {

            $this->upvoteAnswer($_POST['user_id'], $_POST['answer_id']);
        }
    }

    public function changeUpvoteColor()
    {
        $stmt = $this->updateUpvoteColor($_POST['user_id']);
        $upvotes = [];

        while ($upvote = $stmt->fetch(PDO::FETCH_ASSOC)) {

            extract($upvote);

            $upvote = [
                "answer_id" => $answer_id,
            ];

            array_push($upvotes, $upvote);
        }

        echo json_encode($upvotes);
    }
}
