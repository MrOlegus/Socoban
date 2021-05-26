<?php

session_start();
 
$status = 0;
if (isset($_POST['login'])) {
    $dbh = new PDO('mysql:dbname=socoban;host=localhost', 'root', '');
    $sth = $dbh->prepare("SELECT * FROM users WHERE `login` = :login");
    $sth->execute(array('login' => $_POST['login']));
    $user = $sth->fetch();
    
    if (md5($_POST['password']) == $user['password']) {
        $_SESSION['user'] = $user['login'];
    } else {
        session_unset();
        $status = 1;
    }
}

header("Location: ./home.php?status=" . $status);