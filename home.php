<?php

session_start();

include 'modules/get_main_template.php';
$page = get_main_template("main_template.html", "home_main.html");

$dbh = new PDO('mysql:dbname=socoban;host=localhost', 'root', '');

$sth = $dbh->prepare("SELECT * FROM news ORDER BY ID");
$sth->execute();

$rows = $sth->fetchAll(PDO::FETCH_ASSOC);
$news = '';
foreach ($rows as $row) {
    $news .= '<p>' . $row['news'] . ' <span class="date">[' . $row['date'] . ']</span> </p>';
}

$page = str_replace('{news}', $news, $page);

if (isset($_GET['status'])) {
    if ($_GET['status'] == 1) {
        $page = str_replace('</body>', '<script>alert("Логин или пароль введены неверно!");</script> </body>', $page);
    }
}

echo $page;