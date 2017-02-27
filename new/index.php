/**
 *@author Elsa Gonzalez
 *@author Pedro Barragan
 *@author Ulises Martinez
 *this section was automatically inserted sing a sh script
 */
<?php
header('Content-Type: application/json');
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
    if(checkBoundsAndOverlap()){
      //ships are ok, placed good
      //build the opponent ship placement
      $game->buildPCFleet();
      //save everyting to a file
      $id = saveBoard();
      echo json_encode(array('response'=>true, 'pid'=>"".$id));
    }
  }
  else {
    setInvalid("Parse error");
  }
}
else{
  setInvalid("Stratergy or ships not specified");
}

function setInvalid($reason){
  $invalid = array();
  $invalid['response'] = false;
  $invalid['reason'] = $reason;
  echo json_encode($invalid);
  die();
}
function saveBoard(){
  $file_id = getFileNameAndId();
  $file_name = $file_id['filename'];
  $id = $file_id['id'];
  global $game;
  $json = json_encode($game);
  $fp = fopen("../games/$file_name", 'w');
  fwrite($fp, $json);
  fclose($fp);
  return $id;
}
function getFileNameAndId(){
  //TODO: fix for 2 digit numbers
  $path = "../games";
  $games = scandir($path);
  $num = 1;
  if(count($games) > 2){
    //case where there are files in here
    //store the name of the last file
    $ids = preg_replace("/[^0-9]/", "", $games);
    sort($ids, SORT_NUMERIC);
    $num = $ids[count($ids)-1]+1;
  }
  return array("filename"=>"g-$num.json", "id"=>$num);
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
          if($components[3] === "true"){
            $components[3] = true;
          }
          else{
            $components[3] = false;
          }
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
  global $game;
  $ships = $game->getShipPlacements();
  $board = $game->getBoard();
  foreach($ships as $shipPlacement){
    if($shipPlacement->isHorizontal){
        if($shipPlacement->getX()+$shipPlacement->getShip()->getSize()>10){
          setInvalid("Ship '".$shipPlacement->getShip()->getName()."' out of bounds");
        }
        for($i = 0; $i<$shipPlacement->getShip()->getSize(); $i++){
          if($board->getValueAt($shipPlacement->getX()+$i, $shipPlacement->getY()) != 0){
            setInvalid("Overlapping ships");
          }
          else{
            $board->setValueAt($shipPlacement->getX()+$i, $shipPlacement->getY(), 1);
          }
        }
    }
    else{
      if($shipPlacement->getY()+$shipPlacement->getShip()->getSize()>10){
        setInvalid("Ship '".$shipPlacement->getShip()->getName()."' out of bounds");
      }
      for($j = 0; $j<$shipPlacement->getShip()->getSize(); $j++){
          if($board->getValueAt($shipPlacement->getX(), $shipPlacement->getY()+$j) != 0){
            setInvalid("Overlapping ships");
          }
          else{
            $board->setValueAt($shipPlacement->getX(), $shipPlacement->getY()+$j, 1);
          }
        }
    }
  }
  return true;
}
?>
