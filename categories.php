<?php
class Categories{
    public array $categoriesArray;
    public $betweenValue;

    //Получение процента категории от общих трат
    public function getPercent($name): double|int
    {
        return $this->categoriesArray[$name]/array_sum($this->categoriesArray);
    }

    //Получение потраченной суммы по имени категории
    public function getValue($name){
        return $this->categoriesArray[$name];
    }

}
?>