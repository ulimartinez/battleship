<?php
class ShipPlacement{
  public $ship;
  public $xcoordinate;
  public $ycoordinate;
  public $isHorizontal;

  function __construct($ship){
    $this->ship = $ship;
  }
  function getShip(){
    return $this->ship;
  }
  function setCoordinate($x, $y){
    $this->xcoordinate = $x;
    $this->ycoordinate = $y;
  }
  function setIsHorizontal($direction){
    $this->isHorizontal = $direction;
  }
}
 ?>
