<?php

//Запись данных в базу данных
foreach ($result as $row) {
    if ($row['category'] == 'Доступные средства')
        continue;

    $databaseCategories->exec("UPDATE categoriesTable SET spent = " . $categories->categoriesArray[$row['category']] . " WHERE category = " . chr(39) . $row['category'] . chr(39) . ";");
}