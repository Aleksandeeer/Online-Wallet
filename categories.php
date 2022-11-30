<?php
class Categories{
    public array $categoriesArray;
    public $betweenValue;

    public function getPercent($name): double|int
    {
        return $this->categoriesArray[$name]/array_sum($this->categoriesArray);
    }

    public function getValue($name){
        return $this->categoriesArray[$name];
    }

}
?>