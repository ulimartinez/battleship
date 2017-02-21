<?php
$toReturn = array();
require_once '../class/game.class.php';
//check if both parameters are present
if(isset($_GET['strategy']) and isset($_GET['ships'])){
  $game = new Game();
  //check if valid
  //check Stratergy
  $strat = $_GET['strategy'];
  if(!$game->stratergy_exists($strat)){
    setInvalid("Unknown Stratergy.");
  }
  $ships_str = $_GET['ships'];
  if(parse_ships($ships_str)){
    //it is well formed, continue
    checkBoundsAndOverlap($game);
  }
  else {
    echo "not";
  }
}
else{
  setInvalid("Stratergy or ships not specified");
}
header('Content-Type: application/json');

function setInvalid($reason){
  $invalid = array();
  $invalid['response'] = false;
  $invalid['reason'] = $reason;
  echo json_encode($invalid);
  die();
}
function parse_ships($ships_str){
  $ships = explode(";", $ships_str);
  if(count($ships) != 5){
    setInvalid("You need 5 ships to play");
  }
  foreach($ships as $ship){
    if(!check_ship_syntax($ship)){
      return false;
    }
  }
  return true;
}
function check_ship_syntax($ship){
  //checks if the syntax for the ship is valid then it stores it's values in the game's ship placements.
  //the game instance
  global $game;
  $components = explode(",", $ship);
  if(count($components) == 4){
    if(filter_var($components[2], FILTER_VALIDATE_INT) AND $components[2] <=10){
      if(filter_var($components[1], FILTER_VALIDATE_INT) AND $components[1] <=10){
        if($components[3] == "false" OR $components[3] == "true"){
          //well formed
          if($game->storeShipPlacement($components)){
            return true;
          }
          else{
            setInvalid("Ship '$components[0]' unknown");
            return false;
          }
        }
      }
    }
  }
  setInvalid("ship not well formed");
}

function checkBoundsAndOverlap($game){
  $ships = $game->getShipPlacements();
  $board = $game->$board;
  foreach($ships as $ship){
    if($ship->isHorizontal){
        if($ship->getX()+$ship->$ship->getSize()>10){
          setInvalid("Ship out of bounds");  
        }
        for($i = $ship->getX(); $i<$ship->getSize(); $i++){
          if($board->getValueAt($i-1, $ship->getY()-1) != 0){
            setInvalid("Overlapping ships");
          }
          else{
            $board->setValueAt($i-1, $ship->getY()-1, 1);
          }
        }
    }
    else{
      if($ship->getY()+$ship->$ship->getSize()>10){
        setInvalid("Ship out of bounds");
      }
      for($j = $ship->getY(); $j<$ship->getSize(); $j++){
          if($board->getValueAt($ship->getX()-1, $j-1) != 0){
            setInvalid("Overlapping ships");
          }
          else{
            $board->setValueAt($ship->getX()-1, $j-1, 1);
          }
        }
    }
  }
}
?>
