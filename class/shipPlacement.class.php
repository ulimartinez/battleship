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
  function getX(){
    return $this->xcoordinate;
  }
  function getY(){
    return $this->ycoordinate;
  }
  function setIsHorizontal($direction){
    if($direction == "false"){
      $this->isHorizontal = false;
    }
    else{
      $this->isHorizontal = true;
    }
  }
  function toJson(){
    return json_encode($this);
  }
}
 ?>
