<?php
$toReturn = array();
require_once '../class/game.class.php';
//check if both parameters are present
if(isset($_GET['strategy']) and isset($_GET['ships'])){
  $game = new Game();
  $ships = $game->getShipPlacements()
  //check if valid
  //check Stratergy
  $strat = $_GET['strategy'];
  if(!$game->stratergy_exists($strat)){
    setInvalid("Unknown Stratergy.");
  }
  $ships_str = $_GET['ships'];
  parse_ships($ships_str);
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
    check_ship_syntax($ship);
  }

  cheackBoundsAndOverlap($ships);
}
function check_ship_syntax($ship){
  $components = explode(",", $ship);
  if(count($components) == 4){
    if(filter_var($components[2], FILTER_VALIDATE_INT) AND $components[2] <=10){
      if(filter_var($components[1], FILTER_VALIDATE_INT) AND $components[1] <=10){
        if($components[3] == "false" OR $components[3] == "true"){
          //well formed
          return true;
        }
      }
    }
  }
  setInvalid("ship not well formed");
}

function checkBoundsAndOverlap($ships){
  foreach($ships as $ship){
      
  }
}
?>
