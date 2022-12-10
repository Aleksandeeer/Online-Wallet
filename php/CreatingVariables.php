<?php
//Инициализация класса категорий
include 'Classes/categories.php';
$categories = new Categories();

//Подключение к базе данных (#1) трат по категориям и получение таблицы
$databaseCategories = new PDO('sqlite:databases/categoriesDB.db');
$result = $databaseCategories->query('SELECT category, spent FROM categoriesTable')->fetchAll(PDO::FETCH_ASSOC);

//Подключение к базе данных (#2) для записи каждой траты
$databaseSpendingStats = new PDO('sqlite:databases/SpendingStats.db');

//Получение значения из промежуточного поля (если таковое имеется)
$betweenValue = $_COOKIE['betweenValue'];

//Получение данных из базы данных
$categories->getDataFromDataBase($result);
