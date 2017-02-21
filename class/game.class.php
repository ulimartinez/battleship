<?php
require_once 'shipPlacement.class.php';
require_once 'ship.class.php';
require_once 'board.class.php';
class Game{
  public $board;
  public $stratergies;
  public $shipPlacements;
  function __construct(){
    $this->board = new Board(10);
    $this->stratergies = array("Smart", "Random", "Sweep");
    $this->shipPlacements = $this->create_ships();
  }
  function getInfoJson(){
    $info = array();
    $info['size'] = $this->board->getSize();
    $info['stratergies'] = $this->stratergies;
    $info['ships'] = $this->getShipInfo();
    return json_encode($info);
  }
  public function create_ships(){
    $ships = array();
    $names = array("Aircraft carrier","Battleship","Frigate","Submarine","Minesweeper");
    $sizes = array(5,4,3,3,2);
    for($i = 0; $i < 5; $i++){
      $ships[] = new ShipPlacement(new Ship($names[$i], $sizes[$i]));
    }
    return $ships;
  }
  private function getShipInfo(){
    $ships_info = array();
    foreach($this->shipPlacements as $placement){
      $ships_info[] = $placement->getShip();
    }
    return $ships_info;
  }
  function storeShipPlacement($ship_info){
    $shipPlacement = $this->ship_exists($ship_info[0]);
    if($shipPlacement){
      //store the coords and value
      $shipPlacement->setCoordinate(intval($ship_info[1]), intval($ship_info[2]));
      $shipPlacement->setIsHorizontal($ship_info[3]);
    }
    else{
      return false;
    }
    return true;
  }
  protected function ship_exists($ship_name){
    foreach($this->shipPlacements as $shipPlacement){
      if(strcasecmp($ship_name, $shipPlacement->getShip()->getName()) == 0){
        return $shipPlacement;
      }
    }
    return null;
  }
  function getShipPlacements(){
    return $this->shipPlacements;
  }
  function getBoard(){
    return $this->board;
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
