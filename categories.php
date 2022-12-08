<?php
class Categories{
    public array $categoriesArray;

    //Конструктор
    public function __construct()
    {
        $this->categoriesArray = array(6);
    }

    //$result == $->query->fetchAll
    public function getDataFromDataBase($result): void
    {
        foreach ($result as $row) {
            $this->categoriesArray[$row['category']] = $row['spent'];
        }

        array_shift($this->categoriesArray);
    }
}
?>