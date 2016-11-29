<?php
    // $dsn = 'mysql:dbname=mini_bbs;host=localhost';
    // $user = 'root';
    // $password = '';
    // $dbh = new PDO($dsn,$user,$password);
    // $dbh->query('SET NAMES utf8');

    $db = mysqli_connect('localhost','root','','mini_bbs') or die(mysqli_connect_error());
      mysqli_set_charset($db,'utf8');

?>