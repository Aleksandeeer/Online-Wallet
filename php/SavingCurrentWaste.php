<?php

//Сохранение текущей затраты в соответствующую категорию
foreach ($_POST as $key => $value) {
    if ($value == "on") {
        if ($betweenValue != 0) {
            //Запись траты в базу данных со всеми покупками
            $databaseSpendingStats->exec("INSERT INTO SpendingPoints VALUES (" .
                chr(39) . $_POST['descriptionField'] . chr(39) . ", " . $betweenValue . ", " . chr(39) . $key . chr(39) . ");");
        }

        $categories->categoriesArray[$key] += $betweenValue;
        setcookie("betweenValue", 0, time() + 1000000, "/");
        break;
    }
}