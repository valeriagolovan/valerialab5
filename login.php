<?php

/**
 * Файл login.php для не авторизованного пользователя выводит форму логина.
 * При отправке формы проверяет логин/пароль и создает сессию,
 * записывает в нее логин и id пользователя.
 * После авторизации пользователь перенаправляется на главную страницу
 * для изменения ранее введенных данных.
 **/

// Отправляем браузеру правильную кодировку,
// файл login.php должен быть в кодировке UTF-8 без BOM.
header('Content-Type: text/html; charset=UTF-8');

// Начинаем сессию.
session_start();

// В суперглобальном массиве $_SESSION хранятся переменные сессии.
// Будем сохранять туда логин после успешной авторизации.
if (!empty($_SESSION['login'])) {
  // Если есть логин в сессии, то пользователь уже авторизован.
  // TODO: Сделать выход (окончание сессии вызовом session_destroy()
  //при нажатии на кнопку Выход).
  // Делаем перенаправление на форму.
  session_destroy();
  header('Location: ./');
}
$message;
// В суперглобальном массиве $_SERVER PHP сохраняет некторые заголовки запроса HTTP
// и другие сведения о клиненте и сервере, например метод текущего запроса $_SERVER['REQUEST_METHOD'].
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
?>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Задание 5 - логин скрин</title>
  <link rel="stylesheet" href="style.css">
  <script src="script.js"></script>
  </head>
  <div>
<form action="" method="post">
  <label>
    Логин
  <input name="login" />
  </label>
  <label>
    Пароль
  <input name="pass" />
  </label>
  <input type="submit" value="Войти" />
</form>
</div>
<?php
if (!empty($_GET['none'])) {
  $txt="Таких данных в бд НЕТ";
    print"<div>" . $txt . "<div>";
}
}
// Иначе, если запрос был методом POST, т.е. нужно сделать авторизацию с записью логина в сессию.
else {

  // TODO: Проверть есть ли такой логин и пароль в базе данных.
  // Выдать сообщение об ошибках.
  $user = 'u24095';
  $pass = '8452445';
  $db = new PDO('mysql:host=localhost;dbname=u20945', $user, $pass, array(PDO::ATTR_PERSISTENT => true));
  $login_id = $_POST['login'];
  $pass = md5($_POST['pass']);
  $stmt = $db->prepare("SELECT user_id FROM login_data WHERE login_id = ? AND pass = ?");
  $stmt->execute([$login_id, $pass]);
  $user_id = $stmt ->fetch(PDO::FETCH_COLUMN);
  if($user_id){
    // Если все ок, то авторизуем пользователя.
    $_SESSION['login'] = $_POST['login'];
    // Записываем ID пользователя.
    $_SESSION['uid'] = $user_id;
    $_COOKIE[session_name()] = "session_true";
    header('Location: ./');
    // Делаем перенаправление.
  //  header('Location: ./');
  }
  else{
    header('Location: ?none=1');
  }
}
?>
