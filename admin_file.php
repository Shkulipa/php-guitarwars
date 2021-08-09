<?php
  require_once('authorize.php');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Guitar Wars - Admin</title>
  <link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body>
  <h2>Guitar Wars - Admin</h2>

    <?php
        require_once('appvars.php');
        require_once('connectvars.php');

        // Connect to the database 
        $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
            or die ('Ошибка подключения к MySQL-серверу');

        // Retrieve the score data from MySQL
        $query = "SELECT * FROM guitarwars ORDER BY score DESC";
        $data = mysqli_query($dbc, $query);

        // Loop through the array of score data, formatting it as HTML 
        echo '<table>';
            while ($row = mysqli_fetch_array($data)) { 
                // Display the score data
                echo '<tr class="scorerow"><td><strong>' . $row['name'] . '</strong></td>';
                echo '<td>' . $row['date'] . '</td>';
                echo '<td>' . $row['score'] . '</td>';
                echo '<td><a href="removescore.php?id=' . $row['id'] . '&amp;date=' . $row['date'] . '&amp;name=' . $row['name'] . '&amp;score=' . $row['score'] . '&amp;screenshot=' . $row['screenshot'] . '">Удалить</a>';

                if ( $row['approved'] == '0' ) {
                  echo ' / <a href="approvescore.php?id=' . $row['id'] . '&amp;date=' . $row['date'] . '&amp;name=' . $row['name'] . '&amp;score=' . $row['score'] . '&amp;screenshot=' . $row['screenshot'] . '&amp;approved=' . $row['approved'] .'">Санкционировать</a></td></tr>';
                } else {
                    echo ' / <a href="approvescore.php?id=' . $row['id'] . '&amp;date=' . $row['date'] . '&amp;name=' . $row['name'] . '&amp;score=' . $row['score'] . '&amp;screenshot=' . $row['screenshot'] . '&amp;approved=' . $row['approved'] .'">Убрать с рейтинга</a></td></tr>';
                }
            }
        echo '</table>';

        mysqli_close($dbc);

        echo '<p><a href="index.php">&lt;&lt; Back to high scores</a></p>';
    ?>
    
</body> 
</html>