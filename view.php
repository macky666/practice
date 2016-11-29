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


    // 中間デーブルに二つのデータの関係がすでに登録されているか
    


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
       <img src="member_picture/<?php echo htmlspecialchars($post['picture']); ?>"  width="48" height="48" alt="<?php echo htmlspecialchars($post['message']); ?>"/>
       <p><?php echo htmlspecialchars($post['message']); ?><span class="name">(<?php echo htmlspecialchars($post['name']); ?>)</span></p>
       <p class="day"><?php echo htmlspecialchars($post['created']); ?></p>

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