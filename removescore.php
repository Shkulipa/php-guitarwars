<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Guitar Wars - удаление рейтинга</title>
  <link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body>
  <h2>Удаление рейтинга</h2>

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
    echo '<p class="error">Извините, ни одного рейтинга не выбрано для удаления.</p>';
}

if (isset($_POST['submit'])) {
    if ($_POST['confirm'] == 'Yes') {
        @unlink(GW_UPLOADPATH . $screenshot);

        $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
          or die('Ошибка подключения к MySQL-серверу');

        $query = "DELETE FROM guitarwars WHERE id = $id LIMIT 1";
        mysqli_query($dbc, $query);
        mysqli_close($dbc);;

        echo '<p>Рейтинг со значением ' . $score . ' для пользователя ' , $name . ' был удален из базы данных';
        echo '<p><a href="admin_file.php">&lt;&lt; Back to admin page</a></p>'; 
    } else {
        echo '<p>Рейтинг не удалён</p>';
    }
} else if ( isset($id) && isset($name) && isset($date) && isset($score) && isset($screenshot) ) {
  echo '<p>Вы уверены, что хотите удалить этот рейтинг?</p>';
  echo '<p><strong>Имя: </strong>' . $name . '<br/><strong>Дата: </strong>' . $date . '<br/><strong>Рейтинг: </strong>' . $score . '</p>';
  echo '<form method="post" action="removescore.php">';
  echo '<input type="radio" name="confirm" value="Yes"/> Да';
  echo '<input type="radio" name="confirm" value="No" checked="checked"/> Нет <br/>';
  echo '<input type="submit" value="Удалить" name="submit" />';
  echo '<input type="hidden" name="id" value="' . $id . '" />';
  echo '<input type="hidden" name="name" value="' . $name . '" />';
  echo '<input type="hidden" name="score" value="' . $score . '" />';
  echo '</form>';

  echo '<p><a href="admin.php">&lt;&lt; Back to admin page</a></p>';
}

?>

</body>
</html>