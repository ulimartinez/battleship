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
        $row[] = false;
      }
    }
  }
  public function getSize(){
    return $this->size;
  }
}
 ?>
