<?php

//Вывод затрат по категориям если мы выбрали какой-то конкретный курс
if (isset($_POST['currencySelect'])) {
    foreach ($categories->categoriesArray as $key => $value) {
        if ($key == 'Доступные средства')
            continue;

        echo '<p><a href = "http://localhost:63342/Online-Wallet/pages/chosenCategory.php?key=' . $key . '">';
        echo $key . ": " . round($categories->categoriesArray[$key] / $_POST['currencySelect'][0], 2);
        echo '</a></p>';
    }

    //Вывод суммарных затрат
    echo "Всего потрачено: " . round((array_sum($categories->categoriesArray) - $categories->categoriesArray['Доступные средства'])
            / $_POST['currencySelect'][0], 2);
} //Вывод затрат по категориям если мы выбирали какой-то конкретный курс некоторое время назад
else {
    foreach ($categories->categoriesArray as $key => $value) {
        if ($key == 'Доступные средства')
            continue;

        echo '<p><a href = "http://localhost:63342/Online-Wallet/pages/chosenCategory.php?key=' . $key . '">';
        echo $key . ": " . $value;
        echo '</a></p>';
    }

    //Вывод суммарных затрат
    echo "Всего потрачено: " . array_sum($categories->categoriesArray) - $categories->categoriesArray['Доступные средства'];
}