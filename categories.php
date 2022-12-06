<?php
class Categories{
    public array $categoriesArray;

    //Конструктор
    public function __construct()
    {
        $this->categoriesArray = array(6);

        if (!array_key_exists("categories", $_COOKIE)) {
            setcookie("categories", json_encode(array()), time() + 1000000, "/");
        }
    }

    //$result == $->query->fetchAll
    public function getDataFromDataBase($result)
    {
        foreach ($result as $row) {
            $this->categoriesArray[$row['category']] = $row['spent'];
        }
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