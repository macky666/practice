<?php 
    session_start();
    require('dbconnect.php');

    $users = array();

    if(isset($_SESSION['id']) && $_SESSION['time'] + 3600 > time()){
        // ログインしている
        $_SESSION['time'] = time();

        $sql = sprintf('SELECT * FROM `members` WHERE id="%d"',
               mysqli_real_escape_string($db,$_SESSION['id'])
               );
        $record = mysqli_query($db,$sql) or die(mysql_error($db));
        $member = mysqli_fetch_assoc($record);

        $user[] = $member;

    }else{
        // ログインしていない
        header('Location; login.php');
        exit();
    }

    // 投稿を記録する
    if(!empty($_POST)){
        if($_POST['message'] !=''){
            $sql = sprintf('INSERT INTO `posts` 
                            SET `member_id`="%d",
                           `message`="%s",
                           `reply_post_id`="%d",
                           `created` = NOW()',
                   mysqli_real_escape_string($db,$member['id']),
                   mysqli_real_escape_string($db,$_POST['message']),
                   mysqli_real_escape_string($db,$_POST['reply_post_id'])
                   );
                   mysqli_query($db,$sql) or die(mysqli_error($db));

      // header('Location; index.php');
      // exit();
        }
    }

    // 投稿を取得する
    $page = '';
    if(isset($_REQUEST['page'])){
        $page = $_REQUEST['page'];
    }
    if($page == ''){
        $page = 1;
    }

    $page = max($page,1);

    // 最終ページを取得する
    $sql = 'SELECT COUNT(*) AS cnt FROM `posts`';
    $recordSet = mysqli_query($db,$sql);
    $table = mysqli_fetch_assoc($recordSet);
    $maxPage = ceil($table['cnt'] / 5);
    $page = min($page,$maxPage);

    $start = ($page - 1) * 5;
    $start = max(0,$start);

    $sql = sprintf('SELECT m.`name`, m.`picture`, p. * 
                    FROM `members` m,`posts` p 
                    WHERE m.`id`=p.`member_id` 
                    ORDER BY p.`created` DESC LIMIT %d,5',
                    $start
                    );
    $posts = mysqli_query($db,$sql) or die(mysqli_error($db));

    // 返信の場合
    $message = '';
    if(isset($_REQUEST['res'])){
        $sql = sprintf('SELECT m.`name`,m.`picture`, p.* 
                        FROM `members` m,posts p 
                        WHERE m.`id`=p.`member_id` 
                        AND p.`id`="%d" 
                        ORDER BY p.`created` DESC',
              mysqli_real_escape_string($db,$_REQUEST['res'])
              );
        $record = mysqli_query($db,$sql) or die(mysqli_error($db));
        $table = mysqli_fetch_assoc($record);
        $message = '@' . $table['name'] . ' ' . $table['message']. ' ';
    }


    // htmlspecialcharsのショートカット
    function h($value){
        return htmlspecialchars($value,ENT_QUOTES,'UTF-8');
    }

    //本文内のURLにリンクを設定
    // function makelink($value){
    //   return mb_ereg_replace("(https?)(://[[:alnum:]¥+¥$¥;¥?¥.%,!#~*/:@$=_-]+)",'<a href="¥1¥2">¥1¥2</a>' . $value);
    // }


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

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN""http://www.w3.org/TR/xhtmll/DTD/xhtmll-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset="utf-8"/>
<link rel="stylesheet" type="text/css" href="style.css" />
  <title>ひとこと掲示板</title>
</head>

<body>
<div id="wrap">
  <div id="head"></div>
    <h1>ひとこと掲示板</h1>
</div>
<div id="content">

  <div style="text-align: right"><a href="logout.php">ログアウト</a>
  </div>
  <form action="" method="post">
    <dl>
      <dt><?php echo htmlspecialchars($member['name']); ?>さん、メッセージをどうぞ</dt>
      <dd>
        <textarea name="message" cols="50" rows="5"><?php echo htmlspecialchars($message); ?></textarea>
          <input type="hidden" name="reply_post_id" value="<?php echo htmlspecialchars($_REQUEST['res']);?>">
      </dd>
    </dl>

    <div>
      <input type="submit" value="投稿する" />
    </div>
  </form>

<?php while($post = mysqli_fetch_assoc($posts)): ?>


    <div class="msg">
      <img src="member_picture/<?php echo h($post['picture']); ?>" width="48" height="48" alt="<?php echo h($post['name']); ?>" />
        <p><?php echo h($post['message']); ?><span class="name">☆<?php echo h($post['name']); ?></span>
        [<a href="index.php?res=<?php echo h($post['id']); ?>">Re</a>]</p>
        <p class="day">
          <a href="view.php?id=<?php echo h($post['id']); ?>">
        <?php echo h($post['created']); ?>
        </a>
        <?php if($post['reply_post_id'] > 0):?>
          <a href="view.php?id=<?php echo h($post['reply_post_id']); ?>"> 返信元のメッセージ</a>
        <?php endif; ?>


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



        <?php if($_SESSION['id'] == $post['member_id']): ?>
          [<a href="edit.php?id=<?php echo $post['id']; ?>" style="color: #00994C;">編集</a>]
          [<a href="delete.php?id=<?php echo h($post['id']); ?>" style="color": #F33;>削除</a>]
        <?php endif; ?>
      </p>

    </div>


<?php endwhile; ?>

    <ul class="paging">
      <?php if($page > 1): ?>
        <li><a href="index/php?page=<?php echo $page - 1; ?>">前のページへ</a></li>
      <?php else: ?>
        <li>前のページへ</li>
      <?php endif; ?>
      <?php if($page < $maxPage): ?>
        <li><a href="index.php?page=<?php echo $page + 1; ?>">次のページへ</a></li>
      <?php else: ?>
        <li>次のページへ</li>
      <?php endif; ?>
    </ul>

</div>  

<div id="foot">
  <p><img src="images/txt_copyright.png" width="136" height="15" alt="(C) H2O SPACE, Mynavi" /></p>
</div>

</body>
</html>