<?php
include_once __DIR__ . '/../models/Images.php';

class ImageController extends Images
{
    public function createImage()
    {
        //$_FILES['img']['name'] works without basename
        $target_file = "uploads/" . basename($_FILES['img']['name']);

        //works without strtolower but we use strtolower just to make sure the extension will be small not capital
        $fileExtension = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        if ($fileExtension === "jpg" || $fileExtension === "jpeg" || $fileExtension === "png") {

            move_uploaded_file($_FILES['img']['tmp_name'], $target_file);

            if ($this->checkExistImage($_POST['user_id']) > 0) {

                $this->updateImage(basename($_FILES['img']['name']), $_POST['user_id']);
            } else {
                $this->setImage(basename($_FILES['img']['name']), $_POST['user_id']);
            }

            $img_name = [
                'userImage' => basename($_FILES['img']['name'])
            ];

            echo json_encode($img_name);
        } else {
            $error = [
                'error' => 'This file is not an image. please provide image in jpg,jpeg or png extension.',
            ];

            echo json_encode($error);
        }
    }
}
