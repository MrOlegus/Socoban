<?php

session_start();

include 'modules/get_main_template.php';
include 'modules/execute_php.php';

$page = get_main_template("main_template.html", "reviews_main.html");

$dbh = new PDO('mysql:dbname=socoban;host=localhost', 'root', '');

if (isset($_POST['review_text'])) {
    $sth = $dbh->prepare("INSERT INTO `reviews` SET `nic` = :nic, `review` = :review, `mark` = :mark");
    $user_name='';
    if (isset($_SESSION['user'])) {
        $user_name = $_SESSION['user'];
    }
        else {
            $user_name = 'Anonymous';
        }
    $sth->execute(array('nic' => $user_name, 'review' => $_POST['review_text'], 'mark' => $_POST['mark']));   
}

$sth = $dbh->prepare("SELECT * FROM `reviews` ORDER BY `ID` DESC");
$sth->execute();

$rows = $sth->fetchAll(PDO::FETCH_ASSOC);
$reviews = '';
            
foreach ($rows as $row) {
  if (isset($_POST['del' . $row['ID']]))
  {
      $sth = $dbh->prepare("DELETE FROM reviews WHERE ID = " . $row['ID']);
      $sth->execute();
      continue;
  }
  
  $good = '';
  $bad = '';
  for ($i = 0; $i < $row['mark']; $i++) {
      $good .= '▮';
  }
  for ($i = 0; $i < 5 - $row['mark']; $i++) {
      $bad .= '▮';
  }
  $reviews .= '<p> <span class="user_name">' . 
              $row['nic'] . 
              '</span>: ' . 
              $row['review'] . 
              ' <span class="good">' . 
              $good . 
              '</span><span class="bad">' . 
              $bad . 
              '</span></p>'; 
  
  if (isset($_SESSION['user']) && $_SESSION['user'] == 'admin') {
    $reviews .= '<form method="post">'
              . '<input type="submit" name="del' . $row['ID'] . '" value="Удалить" />'
              . '</form>';
  }
}

$page = str_replace('{reviews}', $reviews, $page);

echo $page;