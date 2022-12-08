<!DOCTYPE html>
<?php
include 'php/CreatingVariables.php';
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
include 'php/PostArrayChecking.php';
include 'php/SavingCurrentWaste.php';
?>

<form method="post">
    <label>
        <input type="text" name="moneyField" readonly="readonly" size="20"
               value=<?php include 'php/EchoAvailableResources.php'; ?>>
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

<?php include "php/PrintCategories.php"; ?>

<!--Кнопка RESET-->
<form method="post">
    <input type="submit" name="resetCategoriesButton"
           class="gradient-button" value="RESET"/>
</form>

<!--Диаграмма-->
<div id="moneyChart" style="width: 500px; height: 400px; background: #a6a6a6"></div>

<?php
include 'php/DataBaseExec.php';
?>

</body>
</html>