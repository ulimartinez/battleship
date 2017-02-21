<?php
class Board{
  public $size;
  protected $grid;

  //TODO: type hint this to int
  function __construct($size){
    if($size > 0){
      $this->size = $size;
      $grid = $this->build_grid($size);
    }
  }
  protected function build_grid($size){
    $grid = array();
    for($i = 0; $i < $size; $i++){
      $row = array();
      for($j = 0; $j < $size; $j++){
        $row[] = 0;
      }
    }
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
