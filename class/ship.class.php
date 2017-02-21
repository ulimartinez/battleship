<?php
class Ship{
  public $name;
  public $size;

  function __construct($name, $size){
    $this->name = $name;
    $this->size = $size;
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
}
 ?>
