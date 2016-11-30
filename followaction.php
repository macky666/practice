<?php 
    session_start();
    require('dbconnect.php');

    // もしログインしていて
    if(isset($_SESSION['id'])){
      // ボタンが押され、かつアクションがfollowの時
        if($_REQUEST['action'] && $_REQUEST['action'] == 'follow'){
            $sql = sprintf('INSERT INTO `followings` 
                          SET `follower_id` = %d,
                              `following_id` = %d',
                               mysqli_real_escape_string($db,$_REQUEST['member_id']),
                               mysqli_real_escape_string($db,$_REQUEST['followid'])
            );
            mysqli_query($db,$sql) or die(mysqli_error($db));
        }

        // 上記と逆のことをして、ボタンを押したらfollowingsテーブルのデータ１件を削除する
        if(!empty($_REQUEST['action']) && $_REQUEST['action'] == 'unfollow'){
              $sql = sprintf('DELETE FROM `followings`
                              WHERE `follower_id` = %d
                              AND `following_id` = %d',
                              mysqli_real_escape_string($db,$_REQUEST['member_id']),
                              mysqli_real_escape_string($db,$_REQUEST['followid'])
              );
              mysqli_query($db,$sql) or die(mysqli_error($db));
        }
    }

    header('location: index.php');
    exit();

 ?>