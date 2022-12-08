<?php

//Сохранение текущей затраты в соответствующую категорию
foreach ($_POST as $key => $value) {
    if ($value == "on") {
        $categories->categoriesArray[$key] += $betweenValue;
        setcookie("betweenValue", 0, time() + 1000000, "/");
        break;
    }
}