<?php
require('../dbconnect.php');
session_start();

    // 各入力値の初期値を設定
    $name = '';
    $email = '';
    $password = '';


    if(!empty($_POST)){
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        // エラー項目の確認
        // ニックネーム未入力チェック
        if($_POST['name'] == ''){
          $error['name'] = 'blank';
        }
        // メールアドレス未入力チェック
        if($_POST['email'] == ''){
          $error['email'] = 'blank';
        }
        // パスワード未入力チェック
        if($_POST['password'] == ''){
          $error['password'] = 'blank';
        }
        // パスワードが４文字以下の場合
        if(strlen($_POST['password']) < 4){
          $error['password'] ='length';
        }
        // 画像ファイルの拡張子チェック
         $filename = $_FILES['image']['name'];
        if(!empty($filename)){
            $ext = substr($filename, -3);
            if($ext != 'jpg' && $ext != 'gif' && $ext != 'png'){
              $error['image'] = 'type';
            }
        }


        // 重複アカウントのチェック
        if(empty($error)){
            $sql = sprintf('SELECT COUNT(*) As cnt 
                            FROM `members` 
                            WHERE `email`="%s"',mysqli_real_escape_string($db,$_POST['email']));
            $record = mysqli_query($db,$sql) or die(mysqli_error($db));
            $table = mysqli_fetch_assoc($record);
            if($table['cnt'] > 0){
              $error['email'] = 'duplicate';
            }
        }

        // エラーがなかった場合の処理
        if(empty($error)){
            // 画像をアップロードする
            $image = date('YmdHis') . $_FILES['image']['name'];
            move_uploaded_file($_FILES['image']['tmp_name'], '../member_picture/' . $image);

            $_SESSION['join'] = $_POST;
            $_SESSION['join']['image'] = $image;
            header('Location: check.php');
            exit();
        }

    }
    

// 書き直し
    if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'rewrite'){
      $_POST = $_SESSION['join'];
      $name = $_POST['name'];
      $email = $_POST['email'];
      $password = $_POST['password'];

      $error['rewrite'] = true;
    }



?>

<p>次のフォームに必要事項をご記入ください</p>

<form action="" method="post" enctype="multipart/form-data">

<dl>
   <dt>ニックネーム<span class="required">(必須)</span></dt>
   <dd><input type="text" name="name" size="35" maxlength="255" value="<?php echo htmlspecialchars($name); ?>">
   <?php if(isset($error['name']) && $error['name'] == 'blank'): ?>
   <p class="error">ニックネームを入力してください</p>
   <?php endif; ?>
   </dd>

   <dt>メールアドレス<span class="required">(必須)</span></dt>
   <dd><input type="text" name="email" size="35" maxlength="255" value="<?php echo htmlspecialchars($email); ?>">
   <?php if(isset($error['email']) && $error['email'] == 'blank'): ?>
   <p class="error">メールアドレスを入力してください</p>
   <?php endif; ?>
   </dd>


   <dt>パスワード<span class="required">(必須)</span></dt>
   <dd><input type="password" name="password" size="10" maxlength="20" value="<?php echo $password; ?>">
    <?php if(isset($error['password']) && $error['password'] == 'blank'): ?>
    <p class="error">パスワードを入力してください</p>
    <?php endif; ?>

    <?php if(isset($error['password']) && $error['password'] == 'length'): ?>
    <p class = "error">パスワードは４文字以上で入力してください</p>
    <?php endif; ?>

   </dd>

   <dt>写真など</dt>
    <dd><input type="file" name="image" size="35" >
    <?php if(isset($error['image']) && $error['image'] == 'type'): ?>
    <p class ="error">恐れ入りますが、改めて画像を指定してください。</p>
    <?php endif; ?>

    <?php if(isset($error['email']) && $error['email'] == 'duplicate'):?>
    <p class="error">指定されたメールアドレスはすでに登録されています。</p>
    <?php endif; ?>
    </dd>


</dl>

<div><input type="submit" value="入力内容を確認する"></div>

</form>