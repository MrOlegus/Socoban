<?php

function sendMail(string $to, string $user) : void
{
    require 'modules/PHPMailer.php';
    require 'modules/SMTP.php';
    require 'modules/Exception.php';

    $mail = new PHPMailer\PHPMailer\PHPMailer();
    $mail->isSMTP();
    $mail->Host   = 'ssl://smtp.mail.ru';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'socobanphp';
    $mail->Password   = 'justpassword';
    $mail->SMTPSecure = 'ssl';
    $mail->Port   = 465;

    $mail->setFrom('socobanphp@mail.ru', 'Админ Админов');
    $mail->addAddress($to, $user);

    $mail->Subject = 'Регистрация на сайте';
    $mail->msgHTML("<html><body>
                    <h2>Здравствуйте, уважаемый " . $user . "!</h2>
                    <p>Спасибо за регистрацию на сайте socoban!</p>
                    <p>Но зачем...</p>
                    </body></html>");

    if ($mail->send()) {
      echo 'Ошибка: ' . $mail->ErrorInfo;
    }
}

session_start();

$registration_text = file_get_contents("templates/registration.html");

$registration_text = str_replace('{general_settings}', file_get_contents('templates/general_settings.html'), $registration_text);
$registration_text = str_replace('{registrationForm}', file_get_contents('templates/registrationForm.html'), $registration_text);

$dbh = new PDO('mysql:dbname=socoban;host=localhost', 'root', '');

if (isset($_POST['login'])) {
    $sth = $dbh->prepare("SELECT * FROM users WHERE `login` = :login");
    $sth->execute(array('login' => $_POST['login']));
    $user = $sth->fetch();
   
    if (!empty($user)) $registration_text = str_replace('</body>', '<script>alert("Такой логин уже существует!");</script></body>', $registration_text); else
    if ($_POST['password'] != $_POST['repeatPassword']) $registration_text = str_replace('</body>', '<script>alert("Пароль и повторный пароль различаются!");</script></body>', $registration_text); else
    if ($_POST['login'] == '') $registration_text = str_replace('</body>', '<script>alert("Введите логин!");</script></body>', $registration_text); else
    if ($_POST['password'] == '') $registration_text = str_replace('</body>', '<script>alert("Введите пароль!");</script></body>', $registration_text); 
        else
    {
        $dbh = new PDO('mysql:dbname=socoban;host=localhost', 'root', '');
        $sth = $dbh->prepare("INSERT INTO users SET `login` = :login, `password` = :password");
        $sth->execute(array('login' => $_POST['login'], 'password' => md5($_POST['password']))); 
        $_SESSION['user'] = $_POST['login'];
        
        sendMail($_POST['email'], $_POST['login']);
        
        $registration_text = str_replace('</body>', '<script>alert("Вы успешно зарегистрированы!");</script></body>', $registration_text);
        header("Location: ./home.php");
    }
}

echo $registration_text;