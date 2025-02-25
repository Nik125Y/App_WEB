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

/*  поля  */
$fio = isset($_POST['fio']) ? $_POST['fio'] : '';
$number = isset($_POST['number']) ? preg_replace('/\D/', '', $_POST['number']) : '';
$email = isset($_POST['email']) ? $_POST['email'] : '';
$date = isset($_POST['date']) ? strtotime($_POST['date']) : '';
$radio = isset($_POST['radio']) ? $_POST['radio'] : '';
$language = isset($_POST['language']) ? $_POST['language'] : '';
$bio = isset($_POST['bio']) ? $_POST['bio'] : '';
$check = isset($_POST['check']) ? $_POST['check'] : '';

$languages = ($language != '') ? implode(", ", $language) : [];
/* проверка на ошибки */
$errors = FALSE;

if (empty($_POST['fio'])) {
	print('Заполните имя.<br/>');
	$errors = TRUE;
}


if(strlen($number) != 11){
	print('Заполните номер.<br/>');
	$errors = TRUE;
}
if ($errors) {
  // При наличии ошибок завершаем работу скрипта.
  exit();
}

// Сохранение в базу данных.

$user = 'u68791'; 
$pass = '1609462'; 
$db = new PDO('mysql:host=localhost;dbname=u68791', $user, $pass,
  [PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]); 

//$inQuery = implode(',', array_fill(0, count($language), '?'));

// Отправка fio, number, email, date, radio, bio
try {
	$stmt = $db->prepare("INSERT INTO form_data (fio, number, email, date, radio, bio) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$fio, $number, $email, $date, $radio, $bio]);
}
catch(PDOException $e){
  print('Error : ' . $e->getMessage());
  exit();
}

header('Location: ?save=1');
