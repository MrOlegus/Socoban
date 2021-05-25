<?php

function get_main_template($template_name, $content_name): string {
    //session_start();

    $result = file_get_contents("templates/$template_name");

    $result = str_replace('{general_settings}', file_get_contents('templates/general_settings.html'), $result);
    $result = str_replace('{header}', file_get_contents('templates/header.html'), $result);
    $result = str_replace('{aside}', file_get_contents('templates/aside.html'), $result);
    $result = str_replace('{main}', file_get_contents("templates/$content_name"), $result);
    $result = str_replace('{footer}', file_get_contents('templates/footer.html'), $result);

    $dbh = new PDO('mysql:dbname=socoban;host=localhost', 'root', '');

    $sth = $dbh->prepare("SELECT * FROM `contacts` ORDER BY `ID`");
    $sth->execute();

    $rows = $sth->fetchAll(PDO::FETCH_ASSOC);
    $contacts = '';
    foreach ($rows as $row) {
        $contacts .= '<div class="footer">' . $row['network'] . ': ' . $row['contact'] . '</div>';
    }
    $result = str_replace('{contacts}', $contacts, $result);
    
    if (isset($_SESSION['user'])) {
        $result = str_replace('{enterCaption}', $_SESSION['user'], $result);    
    } 
        else
        {
            $result = str_replace('{enterCaption}', 'Войти', $result); 
        }
    
    return $result;
}