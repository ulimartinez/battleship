<?php
spl_autoload_register(function ($class_name) {
    include $class_name . '.class.php';
});
class Game{
  public $board;
  public $boardpc;
  public $stratergies;
  public $shipPlacements;
  public $shipPlacementspc;
  public $currentStrategy;
  public $isUserTurn;

  function __construct(){
    $this->board = new Board(10);
    $this->boardpc = new Board(10);
    $this->stratergies = array("Smart", "Random", "Sweep");
    $this->shipPlacements = $this->create_ships();
    $this->shipPlacementspc = $this->create_ships();
    $this->isUserTurn = true;
  }
  public static function createFromJson($json_str){
    //TODO: check if pc ship placement works correctly
    $game = json_decode($json_str);
    //create the object
    $tmp_game = new self();
    $tmp_game->currentStrategy = $game->currentStrategy;
    $tmp_game->board->setGrid($game->board->grid);
    $tmp_game->boardpc->setGrid($game->boardpc->grid);
    foreach ($game->shipPlacements as $tmp_placement) {
      $tmp_ship = $tmp_game->ship_exists($tmp_placement->ship->name);
      if($tmp_ship){
        $tmp_ship->setCoordinate($tmp_placement->xcoordinate, $tmp_placement->ycoordinate);
        $tmp_ship->setIsHorizontal($tmp_placement->isHorizontal);
      }
    }
    foreach ($game->shipPlacementspc as $tmp_placement) {
      $tmp_ship = $tmp_game->ship_exists($tmp_placement->ship->name, true);
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
    $info['strategies'] = $this->stratergies;
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
  function getStrategy(){
    return $this->currentStrategy;
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
  protected function ship_exists($ship_name, $isPc = false){
    $placements = $this->shipPlacements;
    if($isPc){
      $placements = $this->shipPlacementspc;
    }
    foreach($placements as $shipPlacement){
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
    foreach($this->shipPlacementspc as $ship){
      $size = $ship->getShip()->getSize();
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
            if($this->boardpc->getValueAt($i,$y) != 0){
              $available = false;
              break;
            }
          }
          if($available){
            $ship->setCoordinate($x,$y);
            $ship->setIsHorizontal($isHorizontal);
            for($i = $x; $i < ($size+$x);$i++){
              $this->boardpc->setValueAt($i,$y,1);
            }
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
            if($this->boardpc->getValueAt($x,$j) != 0){
              $available = false;
              break;
            }
          }
          if($available){
            $ship->setCoordinate($x,$y);
            $ship->setIsHorizontal($isHorizontal);
            for($j = $y; $j < ($size+$y);$j++){
              $this->boardpc->setValueAt($x,$j,1);
            }
          }
        }
      }
    }
  }

  function set_strategy($currentStrategy){
    $this->currentStrategy = $currentStrategy;
  }

  function shotIsValid($x,$y){
    if($this->isUserTurn){
      $currVal = $this->boardpc->getValueAt($x,$y);
    }
    else{
      $currVal = $this->board->getValueAt($x,$y);
    }
    if($currVal > 1){
      return false;
    }
    else{
      return true;
    }
  }

  function hitBoat($x,$y){
    if($this->isUserTurn){
      $currBoard = $this->boardpc;
    }
    else{
      $currBoard = $this->board;
    }
    $currVal = $currBoard->getValueAt($x,$y);
    if($currVal == 0){
      $boardpc->setValueAt($x,$y,3);
      return false;
    }
    else{
      return $this->findShip($x,$y);
    }
  }

  private function findShip($x,$y){
    if($this->isUserTurn){
      $ships = $this->shipPlacementspc;
    }
    else{
      $ships = $this->shipPlacements;
    }
    foreach($ships as $ship){
      $size = $ship->getShip()->getSize();
      if($ship->isHorizontal()){
        if($ship->getY() == $y){
          for($i = $ship->getX(); $i < $ship->getX()+$size; i++){
            if($i == $x){
              $shotIndex = $i-$ship->getX();
              handleShot($x,$y,$ship,$shotIndex);
              return $ship;
            }
          }
        }
      }
      else{
        if($ship->getX() == $x){
          for($j = $ship->getY(); $j < $ship->getY()+$size; $j++){
            if($j == $y){
              $shotIndex = $j-$ship->getY();
              handleShot($x,$y,$ship,$shotIndex);
              return $ship;
            }
          }
        }
      }
    }
  }

  private function handleShot($x,$y,$shotShip,$shotIndex){
    //modify board
    if($this->isUserTurn){
      $this->boardpc->setValueAt($x,$y,2);
    }
    else{
      $this->board->setValueAt($x,$y,2);
    }
    //modify ship
    $shotShip->getShip()->isShotAt($shotIndex);
  }

  funtion isSunk($shotShip){
    return $shotShip->getShip()->isSunk();
  }

  function isWon(){
    if($this->isUserTurn){
      $currShips = $this->shipPlacementspc;
      $this->isUserTurn = false;
    }
    else{
      $currShips = $this->shipPlacements;
      $this->isUserTurn = true;
    }
    $won = true;
    foreach($currShips as $ship){
      if(!$ship->getShip()->isSunk()){
        $won = false;
        break;
      }
    }
    return $won;
  }
}
?>
