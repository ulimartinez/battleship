<?php
class Ship{
  public $name;
  public $size;
  public $shotAt;
  public $isSunk;

  function __construct($name, $size){
    $this->name = $name;
    $this->size = $size;
    $this->isSunk = false;
    $this->shotAt = array();
    for($i = 0; $i < $size; $i++){
      $this->shotAt[] = 0;
    }
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
  function setShotAtArray($arr){
    $this->shotAt = $arr;
  }

  function isSunk(){
    return $this->isSunk;
  }
}
 ?>
