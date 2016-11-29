<?php 
    session_start();
    require('dbconnect.php');

    // もしログインしていて
    if(isset($_SESSION['id'])){
        // ボタンが押され、かつアクションがlikeの時
        if(!empty($_REQUEST['action']) && $_REQUEST['action'] == 'like'){
            $sql = sprintf('INSERT INTO `likes` 
                            SET `member_id`=%d,
                                `tweet_id`=%d',
                   mysqli_real_escape_string($db,$_REQUEST['member_id']),
                   mysqli_real_escape_string($db,$_REQUEST['id'])
              );
            mysqli_query($db,$sql) or die(mysqli_error($db));
        }

        // 上記と逆のことをして、ボタンを押したらlikesテーブルのデータ１件を削除する
        if(!empty($_REQUEST['action']) && $_REQUEST['action'] == 'unlike'){
            $sql = sprintf('DELETE FROM `likes`
                            WHERE `member_id` = %d
                            AND `tweet_id` = %d',
                   mysqli_real_escape_string($db,$_REQUEST['member_id']),
                   mysqli_real_escape_string($db,$_REQUEST['id'])
                            );
            mysqli_query($db,$sql) or die(mysqli_error($db));
        }
    }

    header('location: index.php');
    exit();

 ?>