<?php
class Categories{
    public array $categoriesArray;
    public $betweenValue;

    //Конструктор
    public function __construct()
    {
        if (!array_key_exists("categories", $_COOKIE)) {
            setcookie("categories", json_encode(array()), time() + 1000000, "/");
        }
    }

    //Получения значений из cookie
    public function getDataFromCookie(): void
    {
        $this->betweenValue = $_COOKIE['betweenValue'];
        $this->categoriesArray = json_decode($_COOKIE['categories'], true);
    }

    //Установление значений cookie
    public function setDataInCookie($cookieType){
        if($cookieType == "betweenValue")
            setcookie("betweenValue", 0, time() + 1000000, "/");
        else if($cookieType == "categories")
            setcookie("categories", json_encode($this->categoriesArray), time() + 1000000, "/");

    }

    //Вывод статистики по всем затратам
    public function echoStats(): void
    {
        foreach ($this->categoriesArray as $key => $value) {
            echo $key . ": " . $value . "\t(" . round($this->categoriesArray[$key] / array_sum($this->categoriesArray), 4) * 100 . "%)" . "<br/>";
        }
        echo "<br/>Всего потрачено: " . array_sum($this->categoriesArray);
    }

}
?>