<?php

echo '<div class="section">';
echo '<div class="skills"><a>Затраты</a></div>';
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
    echo '<span class="name">' . '<p><a href = "http://localhost:63342/Online-Wallet/pages/chosenCategory.php?key=' . $key . '">' .$key . " (" . $categories->categoriesArray[$key] . ")" . "</a></p>" . '</span>';
    echo '</div>';
}

echo '</div></div></div>';