<?php
    session_start();
    require('../dbconnect.php');

    if(!isset($_SESSION['join'])){
        header('Location: index.php');
        exit();
    }

    if(!empty($_POST)){
     // 登録処理をする
        $sql = sprintf('INSERT INTO `members` SET `name`="%s", `email`="%s",`password`="%s",`picture`="%s",`created`="%s"',
        mysqli_real_escape_string($db,$_SESSION['join']['name']),
        mysqli_real_escape_string($db,$_SESSION['join']['email']),
        mysqli_real_escape_string($db,sha1($_SESSION['join']['password'])),
        mysqli_real_escape_string($db,$_SESSION['join']['image']),date('Y-m-d H:i:s')
        );
        mysqli_query($db,$sql) or die(mysqli_error($db));
        unset($_SESSION['join']);

        header('Location: thanks.php');
        exit();
    }
?>


<form action="check.php" method="post">
  <input type="hidden" name="action" value="submit" />
  <dl>
    <dt>ニックネーム</dt>
    <dd>
      <?php echo htmlspecialchars($_SESSION['join']['name']);?>
    </dd>

    <dt>メールアドレス</dt>
    <dd>
      <?php echo htmlspecialchars($_SESSION['join']['email']);?>
    </dd>

    <dt>パスワード</dt>
    <dd>
      **
    </dd>

    <dt>写真など</dt>
    <dd>
      <img src="../member_picture/<?php echo htmlspecialchars($_SESSION['join']['image']); ?>" width="100" height="100" alt="" >

    </dd>
  </dl>

   <div><a href="index.php?action=rewrite">&laquo:&nbsp:書き直す</a>
   <input type="submit" value="登録する"></div>


</form>