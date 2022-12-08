<?php

if (isset($_POST['currencySelect'])) {
    echo htmlspecialchars(round($categories->categoriesArray['Доступные средства'] / $_POST['currencySelect'][0], 2));}
else{
    echo htmlspecialchars($categories->categoriesArray['Доступные средства']);}