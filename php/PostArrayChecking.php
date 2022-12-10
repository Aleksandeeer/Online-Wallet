<?php

if ($_POST) {
    //Проверки
    if (($_POST['betweenMoneyField'] > $categories->categoriesArray['Доступные средства']) && isset($_POST['subtractButton'])) {
        echo '<script>alert("Warning: the subtracted value is more than the quantity\n\n" +
 "Предупреждение: вычитаемое количество денег больше количества денег")</script>';
    } else if ((isset($_POST['addButton']) || isset($_POST['subtractButton'])) && $_POST['betweenMoneyField'] == '') {
        echo '<script>alert("Warning: the intermediate value is empty / Предупреждение: промежуточное значение пустое")</script>';
    }

    //Действия кнопок
    if (isset($_POST['resetButton'])) {
        //Обнуление доступных средств
        $categories->categoriesArray['Доступные средства'] = 0;

        $databaseCategories->exec("UPDATE categoriesTable SET spent = 0 WHERE category = 'Доступные средства'");
    } else if (isset($_POST['resetCategoriesButton'])) {
        //Обнуление категорий
        foreach ($categories->categoriesArray as $key => $value) {
            if ($key == 'Доступные средства') {
                continue;
            }

            $categories->categoriesArray[$key] = 0;
        }

        include 'DataBaseExec.php';

        $databaseSpendingStats->exec('DELETE FROM SpendingPoints;');

    } else if (isset($_POST['addButton']) && strlen($_POST["betweenMoneyField"]) > 0) {
        //Добавление средств в кошелёк
        $categories->categoriesArray['Доступные средства'] += $_POST['betweenMoneyField'];

        $databaseCategories->exec("UPDATE categoriesTable SET spent = " . $categories->categoriesArray['Доступные средства'] . " WHERE category = 'Доступные средства'");
    } else if (isset($_POST['subtractButton']) && strlen($_POST["betweenMoneyField"]) > 0 && ($_POST['betweenMoneyField'] <= $categories->categoriesArray['Доступные средства'])) {
        //Вычитаем трату из доступных средств
        $categories->categoriesArray['Доступные средства'] -= $_POST['betweenMoneyField'];

        $databaseCategories->exec("UPDATE categoriesTable SET spent = " . $categories->categoriesArray['Доступные средства'] . " WHERE category = 'Доступные средства'");

        //Записываем трату в куки
        setcookie("betweenValue", $_POST['betweenMoneyField'], time() + 10000, "/");

        //Редирект на страницу выбора категорий
        header("Location:http://localhost:63342/" . basename(getcwd()) . "/pages/categoriesPage.html");
    }
}