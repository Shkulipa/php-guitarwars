<?php
  require_once('authorize.php');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Guitar Wars - Approve a High Score</title>
  <link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body>
  <h2>Guitar Wars - Approve a High Score</h2>

<?php
require_once 'appvars.php';
require_once 'connectvars.php';



if (isset($_GET['id']) && isset($_GET['date']) && isset($_GET['name']) && isset($_GET['score']) && isset($_GET['screenshot'])) {
  $id = $_GET['id'];
  $date = $_GET['date'];
  $name = $_GET['name'];
  $score = $_GET['score'];
  $screenshot = $_GET['screenshot'];
} else if (isset($_POST['id']) && isset($_POST['name']) && isset($_POST['score'])) {
  $id = $_POST['id'];
  $name = $_POST['name'];
  $score = $_POST['score'];
} else {
  echo '<p class="error">Извините, ни одного рейтинга не выбрано для санкционирования.</p>';
}

if (isset($_POST['submit'])) {
    if($_POST['confirm'] == 'Yes') {
        $approved_val = 1;
    } else {
        $approved_val = 0;
    }

    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
    or die('Ошибка подключения к MySQL-серверу');

    // Retrieve the score data from MySQL
    $query = "UPDATE guitarwars SET approved = $approved_val WHERE id = '$id'";
    mysqli_query($dbc, $query);

    if(mysqli_query($dbc, $query)) {
        echo '<p>Рейтинг ' . $score . ' для пользователя ' . $name . ' Успешно санкционирован</p>';
    } else {
        echo '<p class="error">Извините, возникли проблемы с санкционированием рейтинга</p>';
    }

    mysqli_close($dbc);
} else if ( isset($id) && isset($name) && isset($date) && isset($score) && isset($screenshot) ) {
  echo '<p>Вы уверены, что хотите Санкционировать этот рейтинг?</p>';
  echo '<p><strong>Имя: </strong>' . $name . '<br/><strong>Дата: </strong>' . $date . '<br/><strong>Рейтинг: </strong>' . $score . '</p>';
  echo '<form method="post" action="approvescore.php">';
  echo '<img src="' . GW_UPLOADPATH . $screenshot . '" width="160" alt="Score image" /><br />';
  if($_GET['approved'] == '1') {
      echo '<label for="confirm">Сейчас в рейтинге: </label>';
      echo '<input type="radio" name="confirm" value="Yes" checked="checked"/> Да';
      echo '<input type="radio" name="confirm" value="No" /> Нет <br/>';
  } else {
      echo '<label for="confirm">Сейчас в рейтинге: </label>';
      echo '<input type="radio" name="confirm" value="Yes" /> Да';
      echo '<input type="radio" name="confirm" value="No" checked="checked"/> Нет <br/>';
  }
  echo '<input type="submit" value="Санкционировать" name="submit" />';
  echo '<input type="hidden" name="id" value="' . $id . '" />';
  echo '<input type="hidden" name="name" value="' . $name . '" />';
  echo '<input type="hidden" name="score" value="' . $score . '" />';
  echo '</form>';
};

echo '<p><a href="admin_file.php">&lt;&lt; Назад к странице &quot;Админ&quot;</a></p>';

?>

<body>
</html>