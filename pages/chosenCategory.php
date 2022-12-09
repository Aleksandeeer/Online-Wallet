<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>

<!--Таблица-->
<table>
    <tbody>
    <?php
    $databaseCategories = new PDO('sqlite:../databases/SpendingStats.db');
    if($result = $databaseCategories->query('SELECT * FROM SpendingPoints WHERE category = ' . chr(39) . $_GET['key'] . chr(39) . ";")->fetchAll(PDO::FETCH_ASSOC)){
        foreach ($result as $row) {
            echo "<tr>";
            echo "<td>" . $row['name'] . "</td>";
            echo "<td>" . $row['spent'] . "</td>";
            echo "<td>" . $row['category'] . "</td>";
            echo "</tr>";
        }
    }
    else{
        echo '<script>
                if(alert("Ошибка: покупки по выбранной категории отсутствуют")){}
                else{window.location.href = "http://localhost:63342/Online-Wallet/main.php";}
                
 </script>';
    }
    ?>
    </tbody>
</table>

</body>
</html>