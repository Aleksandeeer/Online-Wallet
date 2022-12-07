<!DOCTYPE html>

<?php
//Инициализация класса категорий
include 'categories.php';
$categories = new Categories();

//Подключение к базе данных
$database = new PDO('sqlite:categoriesDB.db');
$result = $database->query('SELECT category, spent FROM categoriesTable')->fetchAll(PDO::FETCH_ASSOC);

$betweenValue = $_COOKIE['betweenValue'];

//Получение данных из базы данных
$categories->getDataFromDataBase($result);
array_shift($categories->categoriesArray);
?>

<html lang="ru">
<head>
    <title>Онлайн кошелёк</title>
    <meta charset="utf-8"/>
    <link rel="stylesheet" href="mystyle.css">
    <script src="https://www.google.com/jsapi"></script>
    <script>
        if ( window.history.replaceState ) {
            window.history.replaceState( null, null, window.location.href );
        }

        google.load("visualization", "1", {packages: ["corechart"]});
        google.setOnLoadCallback(drawChart);

        function drawChart() {
            var data = google.visualization.arrayToDataTable(<?php
                echo "[['Категория', 'Затраты'],";
                foreach ($categories->categoriesArray as $key => $value) {
                    if ($key == 'Доступные средства')
                        continue;
                    echo "['" . $key . "', " . round($categories->categoriesArray[$key] / array_sum($categories->categoriesArray), 4) * 100 . "]";
                    if ($key == array_key_last($categories->categoriesArray)) {
                        continue;
                    } else {
                        echo ",";
                    }
                }
                echo "]"; ?>);
            var options = {
                title: 'Статистика по потраченным средствам',
                is3D: true,
                pieResidueSliceLabel: 'Остальное'
            };
            var chart = new google.visualization.PieChart(document.getElementById('moneyChart'));
            chart.draw(data, options);
        }
    </script>
</head>
<body style="text-align:center;">
<h1 style="font-family: 'Courier New',sans-serif">Кошелёк</h1>
<h3 style="font-family: 'Courier New',sans-serif">Тинькофф Банк</h3>

<?php

if ($_POST) {

    //Проверки
    if (($_POST['betweenMoneyField'] > $categories->categoriesArray['Доступные средства']) && isset($_POST['subtractButton'])) {
        echo '<script>alert("Warning: the subtracted value is more than the quantity\n\n" +
 "Предупреждение: вычитаемое количество денег больше количества денег")</script>';
    } else if ((isset($_POST['addButton']) || isset($_POST['subtractButton'])) && $_POST['betweenMoneyField'] == '') {
        echo '<script>alert("Warning: the intermediate value is empty / Предупреждение: промежуточное значение пустое")</script>';
    }

    //Действия кнопок
    if (isset($_POST['resetButton'])) {
        //Обнуление доступных средств
        $categories->categoriesArray['Доступные средства'] = 0;
    }
    else if (isset($_POST['resetCategoriesButton'])) {
        //Обнуление массива
        foreach ($categories->categoriesArray as $key=>$value){
            if($key == 'Доступные средства'){
                continue;
            }

            $categories->categoriesArray[$key] = 0;
        }
    }
    else if (isset($_POST['addButton']) && strlen($_POST["betweenMoneyField"]) > 0) {
        //Добавление средств в наш кошелёк
        $categories->categoriesArray['Доступные средства'] += $_POST['betweenMoneyField'];
    }
    else if (isset($_POST['subtractButton']) && strlen($_POST["betweenMoneyField"]) > 0 && ($_POST['betweenMoneyField'] <= $categories->categoriesArray['Доступные средства'])) {
        //Вычитаем трату из наших средств
        $categories->categoriesArray['Доступные средства'] -= $_POST['betweenMoneyField'];

        //Записываем трату в куки
        setcookie("betweenValue", $_POST['betweenMoneyField'], time() + 10000, "/");

        //Редирект на страницу выбора категорий
        header("Location:http://localhost:63342/" . basename(getcwd()) . "/categoriesPage.php");
    }
}

//Сохранение текущей затраты в соответствующую категорию
foreach ($_POST as $key => $value) {
    if ($value == "on") {
        $categories->categoriesArray[$key] += $betweenValue;
        setcookie("betweenValue", 0, time() + 1000000, "/");
        break;
    }
}

?>

<form method="post">
    <input type="text" name="moneyField" readonly="readonly" size="20"
           value=<?php echo htmlspecialchars($categories->categoriesArray['Доступные средства']); ?>>

    <input type="submit" name="resetButton"
           class="gradient-button" value="Reset"/>

    <br/><br/>

    <input type="submit" name="subtractButton"
           class="gradient-button" value="-"/>

    <label>
        <input class="text-field" type="text" name="betweenMoneyField"
               onkeyup="this.value = this.value.replace(/[^\d]/g,'');" size="13">
    </label>

    <input type="submit" name="addButton"
           class="gradient-button" value="+"/>

    <br/>
    <br/>
</form>

<?php
//Вывод затрат по категориям
foreach ($categories->categoriesArray as $key => $value) {
    if ($key == 'Доступные средства')
        continue;

    echo $key . ": " . $value . "<br/>";
};

//Вывод суммарных затрат
echo "<br/>Всего потрачено: " . array_sum($categories->categoriesArray) - $categories->categoriesArray['Доступные средства'];
?>

<form method="post">
    <input type="submit" name="resetCategoriesButton"
           class="gradient-button" value="RESET"/>
</form>

<div id="moneyChart" style="width: 500px; height: 400px; background: #a6a6a6"></div>

<?php
//Запись данных в базу данных
foreach ($result as $row) {
    $database->exec("UPDATE categoriesTable SET spent = " . $categories->categoriesArray[$row['category']] . " WHERE category = " . chr(39) . $row['category'] . chr(39) . ";");
}
?>

</body>
</html>