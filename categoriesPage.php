<!DOCTYPE html>
<html>
<head>
    <title>Выбор категории</title>
    <meta charset="utf-8"/>
</head>
<body style="text-align:center;">
<h1>Кошелёк</h1>
<h4>Выберите категорию товара:</h4>

<form method="post">
    <div>
        <input type="checkbox" id="home-food" name="Продукты">
        <label for="home-food">Продукты</label><br>
    </div>

    <div>
        <input type="checkbox" id="not-home-food" name="Еда (не дома)">
        <label for="not-home-food">Еда (не дома)</label><br>
    </div>

    <div>
        <input type="checkbox" id="transport" name="Общественный транспорт">
        <label for="transport"">Общественный транспорт</label><br>
    </div>

    <div>
        <input type="checkbox" id="taxi" name="Такси">
        <label for="taxi">Такси</label><br>
    </div>

    <div>
        <input type="checkbox" id="gifts" name="Подарки">
        <label for="gifts">Подарки</label><br>
    </div>

    <!--name - for POST, id - for LABEL-->
    <div>
        <input type="checkbox" id="medicine" name="Медицина">
        <label for="medicine">Лекарства и лечение</label><br>
    </div>

    <input formaction="http://localhost:63342/Online-Wallet/main.php" type="submit" class="button" name="Accept"
           value="Подтвердить">
</form>
</body>
</html>