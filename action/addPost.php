<?php
session_start();
if (empty($_SESSION['user']['firstname'])) {
    header('Location: ../forum/index.php');
} else
    //добавление поста в бд 
    if (!empty($_POST)) {
        if (!empty($_POST['heading']) && !empty($_POST['body'])) {
            require('../database/db_connect.php');
            $user = getUserByUsername($db, $_SESSION['user']['username']);
            var_dump($user, $_SESSION['user']['username']);

            $statement = $db->prepare('INSERT INTO `posts` (`heading`, `body`, `user_id`)
            VALUES (:heading, :body, :user_id)');
            $statement->bindParam(
                ':heading',
                $_POST['heading'],
            );
            $statement->bindParam(
                ':body',
                $_POST['body'],
            );
            $statement->bindParam(
                ':user_id',
                $user['id'],
            );

            $statement->execute();
            header('Location: ../forum/index.php');
        } else {
            $_SESSION['error'] = 'Пришла неполная форма';
            header('Location: ../pages/addpost.php');
        }
    } else {
        $_SESSION['error'] = 'Форма не пришла';
        header('Location: ../pages/addpost.php');
    }