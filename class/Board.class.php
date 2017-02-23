<?php
class Board{
  public $size;
  public $grid;

  //TODO: type hint this to int
  function __construct($size){
    if($size > 0){
      $this->size = $size;
      $this->grid = $this->build_grid($size);
    }
  }
  protected function build_grid($size){
    $grid = array();
    for($i = 0; $i < $size; $i++){
      $row = array();
      for($j = 0; $j < $size; $j++){
        $row[] = 0;
      }
      $grid[] = $row;
    }
    return $grid;
  }
  public function getGrid(){
    return $this->grid;
  }
  public function setGrid($grid){
    $this->grid = $grid;
  }
  public function setValueAt($x, $y, $val){
    //set the value at the coordinate x,y
    $this->grid[$x][$y] = $val;
  }
  public function getValueAt($x, $y){
    //get the value at the coordinate x,y
    return $this->grid[$x][$y];
  }
  public function getSize(){
    return $this->size;
  }
}
 ?>
