<?php 
    echo "問題① 変数";
    echo "<br>";
    // 自分のフルネームが入った変数を用意し、画面に出力する
    // ※ 最後に改行を２つ入れること

    $namae ='Masaaki Saito';
    echo $namae;
    echo '<br>';
    echo '<br>';


    echo "問題② 配列";
    echo "<br>";
    // HTML, CSS, PHP, MySQL, CentOS, Vagrantという要素が
    // 入った配列データを作成し、一要素ずつ画面に出力する
    // (一要素出力毎に改行をすること。繰り返しなどは使用しない)
    // ※ 最後に改行を２つ入れること

    $element = array('HTML', 'CSS', 'PHP', 'MySQL', 'CentOS', 'Vagrant');
    
      echo $element[0];
      echo "<br>";
      echo $element[1];
      echo "<br>";
      echo $element[2];
      echo "<br>";
      echo $element[3];
      echo "<br>";
      echo $element[4];
      echo "<br>";
      echo $element[5];
      echo "<br>";
      echo "<br>";


    


    echo "問題③ 連想配列";
    echo "<br>";
    // 自分の情報が要素として入った連想配列を作成し、一要素ずつ画面に出力する (一要素出力毎に改行をすること。繰り返しなどは使用せず)
    // 要素には、name, age, nationality, gender, email, telを入れること
    // ※ 最後に改行を２つ入れること

    $information = array('name'=>'Masaaki','age'=>18,'nationality'=>'Japan','gender'=>'male','email'=>'test@gmail.com','tel'=>12345678);

    echo $information['name'];
    echo "<br>";
    echo $information['age'];
    echo "<br>";
    echo $information['nationality'];
    echo "<br>";
    echo $information['gender'];
    echo "<br>";
    echo $information['email'];
    echo "<br>";
    echo $information['tel'];
    echo "<br>";
    echo "<br>";


    echo "問題④ foreach文";
    echo "<br>";
    // foreach文を使い、問題②の配列要素をすべて画面に出力する (一要素出力毎に改行をすること。)
    // ※ 最後に改行を２つ入れること

    foreach($information as $informations){
     echo $informations;
     echo "<br>";
     echo "<br>";
    }

    echo "問題⑤ for文";
    echo "<br>";
    // for文を使い、問題②の配列要素をすべて画面に出力する (一要素出力毎に改行をすること。)
    // 固定の数値は一切使わず、配列要素数の変化に対応できるコードにすること
    // ※ 最後に改行を２つ入れること

     for($i = 0; $i <=5;$i++){
        echo $element[$i];
        echo "<br>";
        echo "<br>";
      }
 ?>

 