<?php
class Ship{
  public $name;
  public $size;
  private $shotAt;
  private $isSunk;

  function __construct($name, $size){
    $this->name = $name;
    $this->size = $size;
    $this->shotAt = array($this->size);
    foreach($this->shotAt as $spot){
      $spot = 0;
    }
    $this->isSunk = false;
  }
  function getName(){
    return $this->name;
  }
  function getSize(){
    return $this->size;
  }
  function toJson(){
    return json_encode($this);
  }

  function setShotAt($shotIndex){
    $this->shotAt[$shotIndex] = 1;
    $this->isSunk = true;
    foreach($this->shotAt as $spot){
      if($spot == 0){
        $this->isSunk = false;
        break;
      }
    }
  }

  function isSunk(){
    return $this->isSunk;
  }
}
 ?>
