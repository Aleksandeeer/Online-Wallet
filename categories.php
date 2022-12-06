<?php
class Categories{
    public array $categoriesArray;
    public $betweenValue;

    //Конструктор
    public function __construct()
    {
        $this->categoriesArray = array(6);

        if (!array_key_exists("categories", $_COOKIE)) {
            setcookie("categories", json_encode(array()), time() + 1000000, "/");
        }
    }

    public function getDataFromDataBase($result){
        foreach ($result as $row) {
            $this->categoriesArray[$row['category']] = $row['spent'];
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
        if($cookieType == "categories")
            //Для массива категорий
            setcookie("categories", json_encode($this->categoriesArray), time() + 1000000, "/");
        else
            //Для всего остального
            setcookie($cookieType, 0, time() + 1000000, "/");

    }

    //$result == $->query->fetchAll
    public function echoDataFromDataBase($result): void
    {
        foreach ($result as $row) {
            $this->categoriesArray[$row['category']] = $row['spent'];

            echo $row['category'] . ": " . $row['spent'] . "<br/>";
        }
    }
}
?>