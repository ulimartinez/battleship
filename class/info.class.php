<?php
require_once 'ship.class.php';
class Info{
  public $size;
  public $stratergies;
  public $ships;
  function __construct(){
    $this->size = 10;
    $this->stratergies = array("Smart", "Random", "Sweep");
    $this->ships = $this->set_ships();
  }
  function getJson(){
    return json_encode($this);
  }
  public function set_ships(){
    $ships = array();
    $names = array("Aircraft carrier","Battleship","Frigate","Submarine","Minesweeper");
    $sizes = array(5,4,3,3,2);
    for($i = 0; $i < 5; $i++){
      $ships[] = new Ship($names[$i], $sizes[$i]);
    }
    return $ships;
  }
}
 ?>
