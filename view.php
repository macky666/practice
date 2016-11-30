<?php 
    session_start();
    require('dbconnect.php');

    // if(empty($_REQUEST)){
    //   header('Location index.php');
    //   exit();
    // }

    // 投稿を取得する
    $sql = sprintf('SELECT m.`name`,m.`picture`,p.* 
                    FROM `members` m, `posts` p 
                    WHERE m.`id`=p.`member_id` 
                    AND p.`id`="%d" 
                    ORDER BY p.`created` DESC',
           mysqli_real_escape_string($db,$_REQUEST['id'])
           );
    $posts = mysqli_query($db,$sql) or die(mysqli_error($db));


    // htmlspecialcharsのショートカット
    function h($value){
        return htmlspecialchars($value,ENT_QUOTES,'UTF-8');
    }


    // likeテーブルからデータを取ってくる
    $sql = sprintf('SELECT * FROM `likes` 
                    WHERE `member_id`=%d',
           mysqli_real_escape_string($db, $_SESSION['id'])
           );
    $rec = mysqli_query($db, $sql) or die(mysqli_error($db));
    $like_tweets = array();
    while($table = mysqli_fetch_assoc($rec)){
        $like_tweets[] = $table['tweet_id'];

    }


    // followingテーブルからデータを取ってくる
    $sql = sprintf('SELECT * FROM `followings`
                    WHERE follower_id = %d',
                    mysqli_real_escape_string($db,$_SESSION['id'])
                    );
    $rec = mysqli_query($db,$sql) or die(mysqli_error($db));
    $follows = array();
     while($table = mysqli_fetch_assoc($rec)){
         $follows[] = $table['following_id'];
     }


 ?>


 

 <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://wwww.w3.org/TR/xhtmll/DTD/xhtmll-transitional.dtd">
 <html xmlns="http://wwww.w3.org/1999/xhtml">
 <head>
 <meta http-equiv="Content-Type" content="text-html"; charset="utf-8">
 <link rel="stylesheet" type="text/css" href="style.css">
   <title>ひとこと掲示板</title>
 </head>
 <body>
 
 <div id="wrap">
   <div id="head">
     <h1>ひとこと掲示板</h1>
   </div>

   <div id="content">
     <p>&laquo;<a href="index.php">一覧に戻る</a></p>

     <?php 
     if($post = mysqli_fetch_assoc($posts)): ?>
     <div class="msg">
       <img src="member_picture/<?php echo h($post['picture']); ?>"  width="48" height="48" alt="<?php echo h($post['message']); ?>"/>
       <p><?php echo h($post['message']); ?><span class="name">(<?php echo h($post['name']); ?>)</span></p>
       <p class="day"><?php echo h($post['created']); ?></p>

        <!-- // いいね！件数のカウント -->
        <?php 
          $sql = sprintf('SELECT COUNT(*) AS cnt 
                          FROM `likes` 
                          WHERE `tweet_id`=%d',
                          mysqli_real_escape_string($db, $post['id']));
          $rec = mysqli_query($db, $sql) or die(mysqli_error($db));
          if($table = mysqli_fetch_assoc($rec)){
              $like_cnt = $table['cnt'];
          }else{
              $like_cnt = 0;
          }
         ?>

         <!-- フォロー件数のカウント -->
         <?php 
          $sql = sprintf('SELECT COUNT(*) AS cnt 
                          FROM `followings` 
                          WHERE `following_id`=%d',
                          mysqli_real_escape_string($db, $post['member_id']));
          $rec = mysqli_query($db, $sql) or die(mysqli_error($db));
          if($table = mysqli_fetch_assoc($rec)){
              $follow_cnt = $table['cnt'];
          }else{
              $follow_cnt = 0;
          }
         ?>

         <!-- ログインIDと投稿されたツイートのmember_idが一致しなければいいねボタンを表示する -->
      <?php if($_SESSION['id'] !== $post['member_id']): ?>
        <!-- 取得していた全ツイートIDの中からいいねが押される対象となるIDを取得 -->
        <?php if(in_array($post['id'],$like_tweets)): ?>
          [<a href="likeaction.php?action=unlike&id=<?php echo $post['id']?>&member_id=<?php echo $_SESSION['id']; ?>">いいねを取り消す</a>]<a >いいね件数：<?php echo $like_cnt; ?></a>]
        <?php else: ?>
          [<a href="likeaction.php?action=like&id=<?php echo $post['id']?>&member_id=<?php echo $_SESSION['id']; ?>">いいね!</a>]<a >いいね件数：<?php echo $like_cnt; ?></a>]
        <?php endif; ?>


        <!-- 取得していた全ツイートIDの中からフォローされる対象となるIDを取得 -->
        <?php if(in_array($post['member_id'],$follows)): ?>
          [[<a href="followaction.php?action=unfollow&followid=<?php echo $post['member_id']?>&member_id=<?php echo $_SESSION['id']; ?>">フォローを外す</a>][<a>フォロワー数：<?php echo $follow_cnt; ?></a>]
        <?php else: ?>
          [[<a href="followaction.php?action=follow&followid=<?php echo $post['member_id']?>&member_id=<?php echo $_SESSION['id']; ?>">フォローする</a>]][<a>フォロワー数：<?php echo $follow_cnt; ?></a>]
        <?php endif; ?>
       <?php endif; ?>

     </div>

     <?php else: ?>
       <p>その投稿は削除されたか、URLが間違えています</p>
     <?php endif; ?>
   </div> 

   <div id="foot">
   <p><img src="images/txt_copyright.png" width="136" height="15" alt="(C) H2O Space.MYNAVI" /></p>
   </div>
 </div>
 </body>
 </html>