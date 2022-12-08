<?php
//Инициализация класса категорий
include 'Classes/categories.php';
include "Classes/currencyConverter.php";
$categories = new Categories();
$currencyConverter = new CurrencyConverter();

$currencyConverter->getCurrency();

//Подключение к базе данных и получение таблицы
$database = new PDO('sqlite:categoriesDB.db');
$result = $database->query('SELECT category, spent FROM categoriesTable')->fetchAll(PDO::FETCH_ASSOC);

//Получение значения из промежуточного поля (если таковое имеется)
$betweenValue = $_COOKIE['betweenValue'];

//Получение данных из базы данных
$categories->getDataFromDataBase($result);
