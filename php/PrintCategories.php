<?php

//Вывод затрат по категориям если мы выбрали какой-то конкретный курс
if (isset($_POST['currencySelect'])) {
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