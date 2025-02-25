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

$errors = FALSE;
if (empty($_POST['fio'])) {
	print('Заполните имя.<br/>');
	$errors = TRUE;
}

$number = isset($_POST['number']) ? preg_replace('/\D/', '', $_POST['number']) : '';
if(strlen($number) != 11){
	print('Заполните номер.<br/>');
	$errors = TRUE;
}


/*
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
val_empty($bio, "биографию")
val_empty($check, "ознакомлен", 2);
*/

// *************
/*
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
    $errors = TRUE;*/
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
	$stmt = $db->prepare("INSERT INTO data SET fio = ?");
	$stmt->execute([$_POST['fio']]);
	$stmt = $db->prepare("INSERT INTO data SET number = ?");
	$stmt->execute([$_POST['number']]);
	$stmt = $db->prepare("INSERT INTO data SET email = ?");
	$stmt->execute([$_POST['email']]);
	$stmt = $db->prepare("INSERT INTO data SET date = ?");
	$stmt->execute([$_POST['date']]);
	$stmt = $db->prepare("INSERT INTO data SET radio = ?");
	$stmt->execute([$_POST['radio']]);
}
catch(PDOException $e){
  print('Error : ' . $e->getMessage());
  exit();
}

//  stmt - это "дескриптор состояния".
 
//  Именованные метки.
//$stmt = $db->prepare("INSERT INTO test (label,color) VALUES (:label,:color)");
//$stmt -> execute(['label'=>'perfect', 'color'=>'green']);
 
//Еще вариант
/*$stmt = $db->prepare("INSERT INTO users (firstname, lastname, email) VALUES (:firstname, :lastname, :email)");
$stmt->bindParam(':firstname', $firstname);
$stmt->bindParam(':lastname', $lastname);
$stmt->bindParam(':email', $email);
$firstname = "John";
$lastname = "Smith";
$email = "john@test.com";
$stmt->execute();
*/

// Делаем перенаправление.
// Если запись не сохраняется, но ошибок не видно, то можно закомментировать эту строку чтобы увидеть ошибку.
// Если ошибок при этом не видно, то необходимо настроить параметр display_errors для PHP.
header('Location: ?save=1');
