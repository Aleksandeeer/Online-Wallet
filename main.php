<!DOCTYPE html>
<?php
include 'php/CreatingVariables.php';
?>

<html lang="ru">
<head>
    <title>Онлайн кошелёк</title>
    <meta charset="utf-8"/>

    <!--Подключение css-файла-->
    <link rel="stylesheet" href="css/MainPageStyle.css">
    <link rel="stylesheet" href="css/MainPage-buttons.css">
    <link rel="stylesheet" href="css/MainPage-text-fields.css">
    <link rel="stylesheet" href="css/MainPage-divs.css">
    <link rel="stylesheet" href="css/MainPage-diagram.css">
</head>
<body>
<h1>Кошелёк</h1>
<h3>Тинькофф Банк</h3>

<script>
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
</script>

<?php
include 'php/PostArrayChecking.php';
include 'php/SavingCurrentWaste.php';
?>

<form method="post">
    <div class="divAvailableMoney">
        <label>
            <input type="text" class="moneyField" name="moneyField" readonly="readonly" size="20"
                   value=<?php include 'php/EchoAvailableResources.php'; ?>>
        </label>

        <button class="btn resetButton" name="resetButton">Сбросить доступные средства</button>
    </div>

    <br/><br/>

    <div class="divAddSubtract">
        <button class="btn subtractButton" name="subtractButton">Покупка</button>

        <label>
            <input class="text-field" type="text" name="betweenMoneyField"
                   onkeyup="this.value = this.value.replace(/\D/g,'');" size="13">
        </label>

        <button class="btn addButton" name="addButton">Добавить</button>
    </div>
    <br/>
    <br/>
</form>

<div class="divDiagram">
    <?php
    include 'php/CreatingDiagramDivs.php';
    ?>

    <!--Кнопка RESET-->
    <form method="post">
        <button class="btn resetButton" name="resetCategoriesButton">Сбросить категории</button>
    </form>
</div>


<?php
include "php/DataBaseExec.php";
?>

</body>
</html>