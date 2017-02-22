<?php
require_once 'shipPlacement.class.php';
require_once 'ship.class.php';
require_once 'board.class.php';
require_once 'strategy.class.php';
class Game{
  public $board;
  public $boardpc;
  public $stratergies;
  public $shipPlacements;
  public $currentStrategy;

  function __construct(){
    $this->board = new Board(10);
    $this->stratergies = array("Smart", "Random", "Sweep");
    $this->shipPlacements = $this->create_ships();
  }
  public static function createFromJson($json_str){
    $game = json_decode($json_str);
    //create the object
    $tmp_game = new self();
    $tmp_game->currentStrategy = $game->currentStrategy;
    $this->board->setGrid($game->board->grid);
    $this->boardpc->setGrid($game->boardpc->grid);
    foreach ($game->shipPlacements as $tmp_placement) {
      $tmp_ship = $this->ship_exists($tmp_placement->ship->name);
      if($tmp_ship){
        $tmp_ship->setCoordinate($tmp_placement->xcoordinate, $tmp_placement->ycoordinate);
        $tmp_ship->setIsHorizontal($tmp_placement->isHorizontal);
      }
    }
    return $tmp_game;
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
        $this->set_strategy($stratery);
        return true;
      }
    }
    return false;
  }

  function buildPCFleet(){
    foreach($ships as $ship){
      $size = $ship->getShip()->getSize();
      echo "attempting to insert boat size ".$size."<br/>";
      $isHorizontal = rand(0,1);
      if($isHorizontal){
        $available = false;
        while(!$available){
      //choose start location
          $x = rand(0,9-$size);
          $y = rand(0,9);
          $available = true;
      //check if it is available for the given size
          for($i = $x; $i < ($size+$x);$i++){
            if($board->getValueAt($i,$y) != 0){
              $available = false;
              break;
            }
          }
          if($available){
            $ship->setCoordinates($x,$y);
            $ship->setIsHorizontal($isHorizontal);
            for($i = $x; $i < ($size+$x);$i++){
              $board->setValueAt($i,$y,1);
            }
            echo "inserted boat size ".$size." at ".$x.", ".$y." horizontally"."<br/>";
          }
        }
      }
      else{
        $available = false;
        while(!$available){
      //echo "attempting to insert boat size ".$size;
      //choose start location
          $x = rand(0,9);
          $y = rand(0,9-$size);
          $available = true;
      //check if it is available for the given size
          for($j = $y; $j < ($size+$y);$j++){
            if($board->getValueAt($x,$j) != 0){
              $available = false;
              break;
            }
          }
          if($available){
            $ship->setCoordinates($x,$y);
            $ship->setIsHorizontal($isHorizontal);
            for($j = $y; $j < ($size+$y);$j++){
              $board->setValueAt($x,$j,1);
            }
            echo "inserted boat size ".$size." at ".$x.", ".$y." vertically"."<br/>";
          }
        }
      }
    }
    echo json_encode($board->getGrid());
    $counter = 0;
    for($i = 0; $i < count($grid); $i++){
      for($j = 0; $j < count($grid[$i]); $j++){
        $counter = $counter + $grid[$i][$j];
      }
    }
  }

  function set_strategy($currentStrategy){
      $statergie=new Strategy($currentStrategy);
      if($statergie->getStrategy() == "Smart"){
        $statergie->smartStrategy();
      } elseif ($statergie->getStrategy() == "Random") {
        $statergie->randomStrategy();
      } else {
        $statergie->sweepStrategy();
      }
    }

}
?>
