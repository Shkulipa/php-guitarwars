<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Guitar Wars - Add Your High Score</title>
  <link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body>
  <h2>Guitar Wars - Add Your High Score</h2>

<?php
  require_once('appvars.php');
  require_once('connectvars.php');

  echo '<p><a href="index.php">&lt;&lt; Back to high scores</a></p>';

  if (isset($_POST['submit'])) {
    // Grab the score data from the POST
    $name = mysqli_real_escape_string($dbc, trim($_POST['name']));
    $score = mysqli_real_escape_string($dbc, trim($_POST['score']));
    $screenshot = mysqli_real_escape_string($dbc, trim($_FILES['screenshot']['name']));
    $screenshot_type = $_FILES['screenshot']['type'];
    $screenshot_size = $_FILES['screenshot']['size'];

    if (!empty($name) && is_numeric($score) && !empty($screenshot)) {
      if( (($screenshot_type == 'image/jpeg') || ($screenshot_type == 'image/pjpeg') || ($screenshot_type == 'image/png') || ($screenshot_type == 'image/gif')) && (($screenshot_size > 0) && ($screenshot_size <= GW_MAXFILESIZE)) ) {
        if ($_FILES['screenshot']['error'] == 0 && is_int($score)) {
          $target = GW_UPLOADPATH . time() . $screenshot;
          if( move_uploaded_file($_FILES['screenshot']['tmp_name'], $target) ) {
            // Connect to the database
            $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
            or die ('Ошибка подключения к MySQL-серверу');
    
              // Write the data to the database
              $query = "INSERT INTO guitarwars (date, name, score, screenshot) VALUES (NOW(), '$name', '$score', '$screenshot')";
    
              // Альтернативный способ(внимательно(одинарные скобки для id и date не ставятся))
              // $query = "INSERT INTO guitarwars (" .
              //     "id," .
              //     "date," .
              //     "name," .
              //     "score," .
              //     "screenshot" .
              // ")" .
    
              // "VALUES (" .
              //     "0," .
              //     "NOW()," .
              //     "'$name'," .
              //     "'$score'," .
              //     "'$screenshot'" .
              // ")";
    
              mysqli_query($dbc, $query);
    
              // Confirm success with the user
              echo '<p>Thanks for adding your new high score!</p>';
              echo '<p><strong>Name:</strong> ' . $name . '<br />';
              echo '<strong>Score:</strong> ' . $score . '</p>';
              echo '<img src="' . GW_UPLOADPATH . $screenshot .'">';
              echo '<p><a href="index.php">&lt;&lt; Back to high scores</a></p>';
    
              // Clear the score data to clear the form
              $name = "";
              $score = "";
              $screenshot = "";
    
              mysqli_close($dbc);
          } 
        } else if ( is_int($score) && $_FILES['screenshot']['error'] != 0) {
          echo '<p class="error">Извините, возникла ошибка при загрузке файла изображения.</p>';
        } else {
          echo '<p class="error">Извините, пожалуйста введите целое число, без пробелом, зяпытх и букв.</p>';
        }
      } else {
        echo '<p class="error">Файл, подтверждающий рейтинг, должен быть файлом иозображения в формате GIF, JPEG или PNG, и его размер не должен превышать более ' . (GW_MAXFILESIZE/1000000) . ' мБ</p>';
        @unlink($_FILES['screenshot']['tmp_name']);
      }
    } else {
      echo '<p class="error">Please enter all of the information to add your high score.</p>';
    }
  }
?>

  <hr />
  <form enctype="multipart/form-data" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
  <input type="hidden" name="MAX_FILE_SIZE" value="5000000">

    <label for="name">Name:</label>
    <input type="text" id="name" name="name" value="<?php if (!empty($name)) echo $name; ?>" /><br />

    <label for="score">Score:</label>
    <input type="number" id="score" name="score" value="<?php if (!empty($score)) echo $score; ?>" />

    <label for="screenshot">Image Fail:</label>
    <input type="file" id="screenshot" name="screenshot"/>

    <hr />
    <input type="submit" value="Add" name="submit" />
  </form>
</body> 
</html>
