<?php
    
    session_start();
    require('dbconnect.php');

    // 初期値を定義
    $email = '';
    $password = '';

    if(isset($_COOKIE['email']) && $_COOKIE['email'] !==''){
        $_POST['email'] = $_COOKIE['email'];
        $_POST['password'] = $_COOKIE['password'];
        $_POST['save'] = 'on';
    }

      //ログインボタンクリック時の処理
    if(!empty($_POST)){
        // ログインの処理
        $email = $_POST['email'];
        $password = $_POST['password'];
        

        if($email !='' && $password !=''){
            $sql = sprintf('SELECT * FROM `members` 
                   WHERE `email`="%s" AND `password`="%s"',
                   mysqli_real_escape_string($db,$email),
                   mysqli_real_escape_string($db,sha1($password)));
            $record = mysqli_query($db,$sql) or die(mysqli_error($db));
          
            if($table = mysqli_fetch_assoc($record)){
                // ログイン成功
                $_SESSION['id'] = $table['id'];
                $_SESSION['time'] = time();

                // ログイン情報を保存する
                if($_POST['save'] == 'on'){
                  setcookie('email',$email,time() + 60*60*24*14);
                  setcookie('password',$password,time() + 60*60*24*14);
                }
                header('Location: index.php');
                exit();
            }
            // ログイン失敗
            else{
                $error['login'] = 'failed';
                echo $table;
            }
    }else{
         $error['login'] = 'blank';
    }

  }


?>

<div id="lead">
  <p>メールアドレスとパスワードを記入してログインしてください。</p>
  <p>入会手続きがまだの方はこちらからどうぞ。</p>
  <p>&raquo;<a href="join/index.php">入会手続きをする</a></p>

</div>

<form action="" method="post">
  <dl>
    <dt>メールアドレス</dt>
    <dd>

      <input type="text" name="email" size="35" maxlength="255" value="<?php echo htmlspecialchars($email); ?>"/>
      <?php if(isset($error['login']) && $error['login'] == 'blank'):?>
      <p class="error">メールアドレスとパスワードをご記入ください</p>
      <?php endif; ?>
      <?php if(isset($error['login']) && $error['login'] == 'failed'): ?>
      <p class="error">ログインに失敗しました。正しくご記入ください。</p>
      <?php endif; ?>
    </dd>

    <dt>パスワード</dt>
    <dd>
      <input type="password" name="password" size="35" maxlength="255" value="<?php echo htmlspecialchars($password); ?>"/>
    </dd>
    <dt>ログイン情報の記録</dt>
    <dd>
      <input id="save" type="checkbox" name="save" value="on"><label for="save">次回からは自動的にログインする</label>
    </dd>
  </dl>

  <div><input type="submit" value="ログインする" />
  <a href="join/index.php"></a>
  </div>  
</form>