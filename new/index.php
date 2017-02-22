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
    if(checkBoundsAndOverlap($game)){
      //ships are ok, placed good
      saveBoard();
    }
>>>>>>> upstream/master
  }
  else {
    setInvalid("Parse error");
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
function saveBoard(){
  $file_name = getFileName();
  global $game;
  $json = json_encode($game);
  $fp = fopen("../games/$file_name", 'w');
  fwrite($fp, $json);
  fclose($fp);
}
function getFileName(){
  $path = "../games";
  $games = scandir($path);
  $num = 1;
  if(count($games) > 2){
    //case where there are files in here
    //store the name of the last file
    $last_file = $games[count($games)-1];
    $base = explode(".", $last_file)[0];
    $num = (int)(explode("-", $base)[1])+1;
  }
  return "g-$num.json";
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

function checkBoundsAndOverlap(){
  $ships = $game->getShipPlacements();
  $board = $game->getBoard();
  foreach($ships as $shipPlacement){
    if($shipPlacement->isHorizontal){
        if($shipPlacement->getX()+$shipPlacement->getShip()->getSize()>11){
          setInvalid("Ship '".$shipPlacement->getShip()->getName()."' out of bounds");
        }
        for($i = 0; $i<$shipPlacement->getShip()->getSize(); $i++){
          if($board->getValueAt($shipPlacement->getX()+$i-1, $shipPlacement->getY()-1) != 0){
            setInvalid("Overlapping ships");
          }
          else{
            $board->setValueAt($shipPlacement->getX()+$i-1, $shipPlacement->getY()-1, 1);
          }
        }
    }
    else{
      if($shipPlacement->getY()+$shipPlacement->getShip()->getSize()>11){
        setInvalid("Ship '".$shipPlacement->getShip()->getName()."' out of bounds");
      }
      for($j = 0; $j<$shipPlacement->getShip()->getSize(); $j++){
          if($board->getValueAt($shipPlacement->getX()-1, $shipPlacement->getY()+$j-1) != 0){
            setInvalid("Overlapping ships");
          }
          else{
            $board->setValueAt($shipPlacement->getX()-1, $shipPlacement->getY()+$j-1, 1);
          }
        }
    }
  }
  return true;
}
?>
