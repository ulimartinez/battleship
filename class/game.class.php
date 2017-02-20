<?php
require_once 'ship.class.php';
require_once 'board.class.php';
class Game{
  public $board;
  public $stratergies;
  public $ships;
  function __construct(){
    $this->board = new Board(10);
    $this->stratergies = array("Smart", "Random", "Sweep");
    $this->ships = $this->create_ships();
  }
  function getInfoJson(){
    $info = array();
    $info['size'] = $this->board->getSize();
    $info['stratergies'] = $this->stratergies;
    $info['ships'] = $this->ships;
    return json_encode($info);
  }
  public function create_ships(){
    $ships = array();
    $names = array("Aircraft carrier","Battleship","Frigate","Submarine","Minesweeper");
    $sizes = array(5,4,3,3,2);
    for($i = 0; $i < 5; $i++){
      $ships[] = new Ship($names[$i], $sizes[$i]);
    }
    return $ships;
  }
  function stratergy_exists($stratery){
    foreach($this->stratergies as $strat){
      if($stratery == $strat){
        return true;
      }
    }
    return false;
  }
}
 ?>
