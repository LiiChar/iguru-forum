<!DOCTYPE html>
<html lang="en">
<?php
session_start();
require("../database/db_connect.php");
$post;
if (!empty($_GET) && $_GET['post_id']) {
    $post = getPostById($db, $_GET['post_id']);
}
$order = null;
if (!empty($_GET) && array_key_exists("order", $_GET) && $_GET['order']) {
    switch ($_GET['order']) {
        case "old":
            $order = "ORDER BY updated_at ASC";
            break;
        case "new":
            $order = "ORDER BY updated_at DESC";
            break;
    }
}
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style/style.css">
    <title><?php print($post['heading']) ?></title>
</head>

<body>
    <?php
    $user = getUserByUsername($db, $_SESSION['user']['username']);
    $comments = getCommentsByPostId($db, $post["id"], $order);

    require("../components/header.php");
    ?>
    <div style="padding: 10px 20vw 10px 20vw; height: fit-content;" class="post_wrapper">
        <div class="icon_user">

            <img class="icon" src="../assets/user.png" alt="user icon">
        </div>

        <div class="post_info">

            <div>
                <span class="post_author_name"><?php print_r($user["username"])  ?></span>
                <span class="post_date"><?php print(date_parse($post["updated_at"])["hour"] . ":" . date_parse($post["updated_at"])["minute"]) ?></span>
            </div>
            <a href="../pages/post.php?post_id=<?php print($post["id"]) ?>" class="post_heading"><?php print_r($post["heading"] . '<br>') ?></a>
            <div style=" height: fit-content;" class="post_body"><?php print_r($post["body"] . '<br>') ?></div>
            <div>
                <span>
                    <a class="post_like" style="<?php getLikeByTypePostId($db, $_SESSION['user']["username"], $post["id"], 'posts') ? print("color: red") : print("inherit") ?>" href=<?php print("../action/addLikes.php?type=posts&id=" . $post["id"]) ?>>&#10084 </a>
                    <?php print(getCountLikesByTypePostId($db, $post["id"], 'posts')) ?></span>
            </div>
        </div>

    </div>
    <div class="message">
        <div class="message_wrapper">
            <div class="message_info">
                <span><?php print(count($comments)) ?> сообщений |</span>
                <form action="../action/changeOrderMessage.php" method="get">Упорядочить по
                    <select onchange="this.form.submit()" name="order">
                        <option <?php array_key_exists("order", $_GET) && $_GET['order'] == "new" ? print("selected") : "" ?> value="new"><button type="submit">новым</button></option>
                        <option <?php array_key_exists("order", $_GET) && $_GET['order'] == "old" ? print("selected") : "" ?> value="old"><button type="submit">старым</button></option>
                    </select>
                </form>
            </div>
            <div class="message_wrapper_input">
                <img class="message_img" src="../assets/user.png" alt="user icon">
                <form class="message_input_wrapper" action="../action/addComment.php" method="post">
                    <div class="message_input">
                        <input placeholder="Напишите ваше мнение..." class="message_input_input" name="body" type="text">
                        <input style="display: none;" name="post_id" value="<?php print($post['id']); ?>" type="text">
                    </div>
                    <div class="message_input_actios">
                        <button type="button" class="message_input_close">Отмена</button>
                        <button type="submit" class="message_input_apply">Написать</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
    <?php
    require("../components/comments_list.php");
    ?>
</body>

</html>