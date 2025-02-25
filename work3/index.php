<!DOCTYPE html>
<html lang="ru">
  <head>
    <meta charset="UTF-8" />
    <link rel="stylesheet" href="style.css" />
    <link rel="stylesheet" href="bootstrap.min.css" />
    <title>Задание_3</title>
  </head>
  <body>
<?php
header('Content-Type: text/html; charset=UTF-8');

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  if (!empty($_GET['save'])) {
    print('Спасибо, результаты сохранены.');
	}
  include('form.php');
  
  exit();
}

function errp($error)
{
    print("<div class='messageError'>$error</div>");
    exit();
}

function val_empty($val, $fio, $o = 0)
{
    if(empty($val))
    {
        if($o == 0)
            errp("Заполните поле $fio.<br/>");
        if($o == 1)
            errp("Выберите $fio.<br/>");
        if($o == 2)
            errp("ознакомьтесь с контрактом<br/>");
        exit();
    }
}
$errors = FALSE;


$fio = isset($_POST['fio']) ? $_POST['fio'] : '';
$number = isset($_POST['number']) ? preg_replace('/\D/', '', $_POST['number']) : '';
$email = isset($_POST['email']) ? $_POST['email'] : '';
$date = isset($_POST['date']) ? strtotime($_POST['date']) : '';
$radio = isset($_POST['radio']) ? $_POST['radio'] : '';
$language = isset($_POST['language']) ? $_POST['language'] : '';
$bio = isset($_POST['bio']) ? $_POST['bio'] : '';
$check = isset($_POST['check']) ? $_POST['check'] : '';

$languages = ($language != '') ? implode(", ", $language) : [];

val_empty($fio, "имя");
val_empty($number, "телефон");
val_empty($email, "email");
val_empty($date, "дата");
val_empty($radio, "пол", 1);
val_empty($language, "языки", 1);
//val_empty($bio, "биографию");
val_empty($check, "ознакомлен", 2);

// *************
if(strlen($fio) > 255)
    $errors = TRUE;
elseif(count(explode(" ", $fio)) < 2)
    $errors = TRUE;
elseif(strlen($number) != 11)
    $errors = TRUE;
elseif(strlen($number) > 255)
    $errors = TRUE;
elseif(!preg_match('/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/', $email))
    $errors = TRUE;
elseif(!is_numeric($date) || strtotime("now") < $date)
    $errors = TRUE;
elseif($radio != "M" && $radio != "W")
    $errors = TRUE;
elseif(count($language) == 0)
    $errors = TRUE;
// *************


if ($errors) {
  // При наличии ошибок завершаем работу скрипта.
  exit();
}

// Сохранение в базу данных.

$user = 'u68791'; 
$pass = '1609462'; 
$db = new PDO('mysql:host=localhost;dbname=u68791', $user, $pass,
  [PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]); 

// Подготовленный запрос. Не именованные метки.
try {
	$stmt = $db->prepare("INSERT INTO data (fio, number, email, date, radio) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$fio, $number, $email, $date, $radio]);
}
catch(PDOException $e){
  print('Error : ' . $e->getMessage());
  exit();
}


// Если запись не сохраняется, но ошибок не видно, то можно закомментировать эту строку чтобы увидеть ошибку.
// Если ошибок при этом не видно, то необходимо настроить параметр display_errors для PHP.
header('Location: ?save=1');
