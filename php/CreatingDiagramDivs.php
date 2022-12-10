<?php

echo '<div class="section">';
echo '<div class="skills">Затраты</div>';
echo '<div class="diagram">';

foreach ($categories->categoriesArray as $key => $value){
    if($key == 'Доступные средства')
        continue;

    if(round($categories->categoriesArray[$key] / (array_sum($categories->categoriesArray) - $categories->categoriesArray['Доступные средства']), 2) * 100 == 0)
        continue;

    echo '<div class="skillBLock">';
    echo '<div class="column">';
    echo '<span>' . round($categories->categoriesArray[$key] / (array_sum($categories->categoriesArray) - $categories->categoriesArray['Доступные средства']), 4) * 100 . "%" . '</span>';
    echo '</div>';
    echo '<span class="name">' . $key . " (" . $categories->categoriesArray[$key] . ")" . '</span>';
    echo '</div>';
}

echo '</div></div></div>';