<!DOCTYPE html>

<?php
include 'categories.php';
$categories = new Categories();

if (!array_key_exists("categories", $_COOKIE)) {
    setcookie("categories", json_encode(array()), time() + 1000000, "/");
}

//Получение данных из cookie
$categories->categoriesArray = json_decode($_COOKIE['categories'], true);
$money = $_COOKIE['money'];
$categories->betweenValue = $_COOKIE['betweenValue'];

?>

<html>
<head>
    <title>Онлайн кошелёк</title>
    <meta charset="utf-8"/>
    <link rel="stylesheet" href="mystyle.css">
    <script src="https://www.google.com/jsapi"></script>
    <script>
        google.load("visualization", "1", {packages: ["corechart"]});
        google.setOnLoadCallback(drawChart);

        function drawChart() {
            var data = google.visualization.arrayToDataTable(<?php
                echo "[['Категория', 'Затраты'],";
                foreach ($categories->categoriesArray as $key => $value) {
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
            var chart = new google.visualization.PieChart(document.getElementById('money'));
            chart.draw(data, options);
        }
    </script>
</head>
<body style="text-align:center;">
<h1>Кошелёк</h1>
<h4>Тинькофф Банк</h4>

<?php

if ($_POST) {

    //Проверки
    if (($_POST['betweenMoneyField'] > $_POST['moneyField']) && isset($_POST['subtractButton'])) {
        echo "Warning: the subtracted value is more than the quantity / Предупреждение: вычитаемое количество денег больше количества денег";
    } else if ((isset($_POST['addButton']) || isset($_POST['subtractButton'])) && $_POST['betweenMoneyField'] == '') {
        echo "Warning: the intermediate value is empty / Предупреждение: промежуточное значение пустое";
    }

    //Действия кнопок
    if (isset($_POST['resetButton'])) {
        $money = 0;
    } else if (isset($_POST['resetCategoriesButton'])) {
        $categories->categoriesArray = array();
    } else if (isset($_POST['addButton']) && strlen($_POST["betweenMoneyField"]) > 0) {
        $money = $_POST['moneyField'] + $_POST['betweenMoneyField'];
    } else if (isset($_POST['subtractButton']) && strlen($_POST["betweenMoneyField"]) > 0 && ($_POST['betweenMoneyField'] < $_POST['moneyField'])) {
        $money = $_POST['moneyField'] - $_POST['betweenMoneyField'];
        setcookie("betweenValue", $_POST['betweenMoneyField'], time() + 1000000, "/");
        header("Location:http://localhost:63342/phpProjects/categoriesPage.php");
    }
}

foreach ($_POST as $key => $value) {
    if ($value == "on") {
        if (!array_key_exists($key, $categories->categoriesArray)) {
            $categories->categoriesArray[$key] = $categories->betweenValue;
        } else {
            $categories->categoriesArray[$key] += $categories->betweenValue;
        }
        setcookie("betweenValue", 0, time() + 1000000, "/");
    }
}

//Хрен пойми зачем, но пока пускай лежит
/*$_COOKIE['money'] = $_POST['moneyField'];
$_COOKIE['categories'] = json_encode($categories->categoriesArray);*/

setcookie("categories", json_encode($categories->categoriesArray), time() + 1000000, "/");
setcookie("money", $money, time() + 1000000, "/");
?>

<form method="post">
    <input type="text" name="moneyField" readonly="readonly" size="20" value=<?php echo htmlspecialchars($money); ?>>

    <input type="submit" name="resetButton"
           class="button" value="Reset"/>

    <br/><br/>

    <input type="submit" name="subtractButton"
           class="button" value="-"/>

    <input type="text" name="betweenMoneyField" size="13">

    <input type="submit" name="addButton"
           class="button" value="+"/>

    <br/>
    <br/>
</form>

<?php
foreach ($categories->categoriesArray as $key => $value) {
    echo $key . ": " . $value . "\t(" . round($categories->categoriesArray[$key] / array_sum($categories->categoriesArray), 4) * 100 . "%)" . "<br/>";
}
echo "<br/>Всего потрачено: " . array_sum($categories->categoriesArray);
?>

<form method="post">
    <input type="submit" name="resetCategoriesButton"
           class="button" value="RESET"/>
</form>

<div id="money" style="width: 500px; height: 400px;"></div>

</body>
</html>