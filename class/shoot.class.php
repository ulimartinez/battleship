<?php
class Shoot{
  public $shoot;
  public $xcoordinate;
  public $ycoordinate;

  function __construct($shoot){
    $this->shoot = $shoot;
  }
  function getShoot(){
    return $this->shoot;
  }
  function setShootCoordinate($x, $y){
    $this->xcoordinate = $x;
    $this->ycoordinate = $y;
  }
  function getX(){
    return $this->xcoordinate;
  }
  function getY(){
    return $this->ycoordinate;
  }
  function toJson(){
    return json_encode($this);
  }
?>
