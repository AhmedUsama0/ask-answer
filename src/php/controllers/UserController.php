<?php
include_once __DIR__ . '/../models/Users.php';

class UserController extends Users
{

    public function createUser()
    {
        $email_exist = false;
        $username_exist = false;
        $account_exist = false;
        $message = [];
        $stmt = $this->getAllUsers();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            if ($email === $_POST['email']) {
                $message = [
                    'error' => 'Email is already exist'
                ];
                $email_exist = true;
            }
            if ($username === $_POST['username']) {
                $message = [
                    'error' => 'Username is already exist'
                ];
                $username_exist = true;
            }
            if ($email === $_POST['email'] && $username === $_POST['username']) {
                $message = [
                    'error' => 'Account is already exist'
                ];
                $account_exist = true;
            }
        }
        if (!empty($message)) {
            echo json_encode($message);
        }

        if (!$email_exist && !$username_exist && !$account_exist) {
            $this->setUser($_POST['username'], $_POST['email'], $_POST['password']);
        }
    }

    public function checkUser()
    {
        $stmt = $this->getUser($_POST['email'], $_POST['password']);
        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            extract($user);
            $userData = [
                'username' => $username,
                'user_id' => $user_id,
                'email' => $email,
                'password' => $password,
                'userImage' => $image,
            ];

            echo json_encode($userData);
        }
    }

    public function getUserProfileData()
    {
        $stmt = $this->fetchUserProfileData($_GET['user_id']);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        extract($user);
        $userData = [
            "username" => $username,
            "userImage" => $image,
        ];

        echo json_encode($userData);
    }
}
