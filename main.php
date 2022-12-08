<!DOCTYPE html><?php
//Инициализация класса категорий
include 'Classes/categories.php';
include "Classes/currencyConverter.php";
$categories = new Categories();
$currencyConverter = new CurrencyConverter();

$currencyConverter->getCurrency();

//Подключение к базе данных и получение таблицы
$database = new PDO('sqlite:categoriesDB.db');
$result = $database->query('SELECT category, spent FROM categoriesTable')->fetchAll(PDO::FETCH_ASSOC);

//Получение значения из промежуточного поля (если таковое имеется)
$betweenValue = $_COOKIE['betweenValue'];

//Получение данных из базы данных
$categories->getDataFromDataBase($result);
?>
<html lang="ru">
<head>
    <title>Онлайн кошелёк</title>
    <meta charset="utf-8"/>
    <link rel="stylesheet" href="mystyle.css">
    <!--Скрипт диаграммы-->
    <script src="https://www.google.com/jsapi"></script>
    <script>
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
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
<body>
<h1>Кошелёк</h1>
<h3>Тинькофф Банк</h3>

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
    } else if (isset($_POST['resetCategoriesButton'])) {
        //Обнуление категорий
        foreach ($categories->categoriesArray as $key => $value) {
            if ($key == 'Доступные средства') {
                continue;
            }

            $categories->categoriesArray[$key] = 0;
        }
    } else if (isset($_POST['addButton']) && strlen($_POST["betweenMoneyField"]) > 0) {
        //Добавление средств в кошелёк
        $categories->categoriesArray['Доступные средства'] += $_POST['betweenMoneyField'];
    } else if (isset($_POST['subtractButton']) && strlen($_POST["betweenMoneyField"]) > 0 && ($_POST['betweenMoneyField'] <= $categories->categoriesArray['Доступные средства'])) {
        //Вычитаем трату из доступных средств
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
    <label>
        <input type="text" name="moneyField" readonly="readonly" size="20"
               value=<?php echo htmlspecialchars($categories->categoriesArray['Доступные средства']); ?>>
    </label>

    <input type="submit" name="resetButton"
           class="gradient-button" value="Reset"/>

    <div>
        <!--Выпадающий список-->
        <select name='currencySelect[]' id='selectId'>
            <script>
                var select = document.getElementById('selectId');
                var currencyArray = <?php echo json_encode($currencyConverter->exchanges); ?>;

                var option = document.createElement("option");
                option.text = 'Российский рубль';
                option.value = '1';
                select.add(option, select[0]);
                var i = 1;

                for (var key in currencyArray) {
                    option = document.createElement("option");
                    option.text = key.substring(1, key.length - 1) + ': ' + currencyArray[key] + '₽';
                    option.value = currencyArray[key];
                    select.add(option, select[i]);
                    i = i + 1;
                }
            </script>
        </select>
        <input name="acceptSubmit" type="submit" value="Подтвердить"/>
    </div>

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
//Вывод затрат по категориям если мы выбрали какой-то конкретный курс
if (isset($_POST['currencySelect'])) {
    echo "_POST: " . $_POST['currencySelect'][0] . "<br/>";

    foreach ($categories->categoriesArray as $key => $value) {
        if ($key == 'Доступные средства')
            continue;

        echo $key . ": " . round($categories->categoriesArray[$key] / $_POST['currencySelect'][0], 2) . "<br/>";
    }

    //Вывод суммарных затрат
    echo "<br/>Всего потрачено: " . round((array_sum($categories->categoriesArray) - $categories->categoriesArray['Доступные средства'])
            / $_POST['currencySelect'][0], 2);
} //Вывод затрат по категориям если мы выбирали какой-то конкретный курс некоторое время назад
else {
    foreach ($categories->categoriesArray as $key => $value) {
        if ($key == 'Доступные средства')
            continue;

        echo $key . ": " . $value . "<br/>";
    }

    //Вывод суммарных затрат
    echo "<br/>Всего потрачено: " . array_sum($categories->categoriesArray) - $categories->categoriesArray['Доступные средства'];
}

?>

<!--Кнопка RESET-->
<form method="post">
    <input type="submit" name="resetCategoriesButton"
           class="gradient-button" value="RESET"/>
</form>

<!--Диаграмма-->
<div id="moneyChart" style="width: 500px; height: 400px; background: #a6a6a6"></div>

<?php
//Запись данных в базу данных
foreach ($result as $row) {
    $database->exec("UPDATE categoriesTable SET spent = " . $categories->categoriesArray[$row['category']] . " WHERE category = " . chr(39) . $row['category'] . chr(39) . ";");
}
?>

</body>
</html>