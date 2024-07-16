<?php

require basePath('vendor/autoload.php');

use Respect\Validation\Validator as v;


function updateUser()
{
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $username = $_POST['username'];
        $user_img_url = $_POST['user_img_url'];
        $email = $_POST['email'];
        $user_type = $_POST['user_type'];
        $status = $_POST['status'];
        $password = $_POST['password'];

        $hashPassword = password_hash($password, PASSWORD_DEFAULT);

        if (v::notEmpty()->validate($username) &&  v::notEmpty()->validate($email)) {
            try {
                query_create('UPDATE users SET
                  username = :username, 
                  email = :email,
                  bio = :bio,
                  user_img_url = :user_img_url,
                  gender = :gender,
                  user_type = :user_type,
                  status = :status,
                  last_update = CURRENT_TIMESTAMP
                    WHERE user_id = :user_id', [
                    'username' => $username,
                    'user_img_url' => $user_img_url,
                    'email' => $email,
                    'user_type' => $user_type,
                    'password' => $hashPassword,
                    'status' => $status,
                ]);
                loadView('component/notification', ['message' => 'Data created sucessfully', 'type' => 'success']);
            } catch (Exception) {
                loadView('component/notification', ['message' => 'User data already exists', 'type' => 'error']);
            }
        } else {
            loadView('component/notification', ['message' => 'Please input valid data', 'type' => 'error']);
        }
    }
}
