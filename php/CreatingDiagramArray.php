<?php
echo "[['Категория', 'Затраты'],";
foreach ($categories->categoriesArray as $key => $value) {
    if ($key == 'Доступные средства')
        continue;

    echo "['" . $key . "', " . round($categories->categoriesArray[$key] / (array_sum($categories->categoriesArray) > 0 ? array_sum($categories->categoriesArray) : 1) , 4) * 100 . "]";
    if ($key == array_key_last($categories->categoriesArray)) {
        continue;
    } else {
        echo ",";
    }
}
echo "]";
