<?php

//Запись данных в базу данных
foreach ($result as $row) {
    $database->exec("UPDATE categoriesTable SET spent = " . $categories->categoriesArray[$row['category']] . " WHERE category = " . chr(39) . $row['category'] . chr(39) . ";");
}