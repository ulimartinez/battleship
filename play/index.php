<?php
//require_once '../new/index.php';

if(isset($_GET['pid']) and isset($_GET['shot']))

  $pid = $_GET['pid'];
  $shot_str = $_GET['shot'];
  if(parse_shoot($shot_str)){
    //it is well formed, continue
    echo 'SHOOT';
    }
    else {
    setShootInvalid("Parse error, shoot not well specified");
  }
  function setShootInvalid($reason){
    $invalid = array();
    $invalid['response'] = false;
    $invalid['reason'] = $reason;
    echo json_encode($invalid);
    die();
  }
  function parse_shoot($shot_str){
    $shoot_coordinates = explode(",", $shot_str);
    if(count($shoot_coordinates) != 2){
      setShootInvalid("coordinates has to be indicated as (x,y) integers");
    }
    else {
      if(filter_var($shoot_coordinates[0], FILTER_VALIDATE_INT) AND $shoot_coordinates[0] <=10) {
        if(filter_var($shoot_coordinates[1], FILTER_VALIDATE_INT) AND $shoot_coordinates[1] <=10) {
            //well fosrmed DO SOMETHING
                return true;
            }
          }
        }
  }
  function isHit(){
  }
  function ack_shot(){
  }
  function isWin(){
  }
  function pid_exists(){

  }

/*{"response": true,
     "ack_shot": {
       "x": 4,
       "y": 5,
       "isHit": false,   // hit a ship?
 "isSunk": false,  // sink a ship?
 "isWin": false,   // game over?
       "ship:" []}       // coordinates (xi,yi)'s of the sunken ship
     "shot": {           //  if isSunk is true
       "x": 5,
       "y": 6,
       "isHit": false,
       "isSunk": false,
       "isWin": false,
       "ship:", []}}

    {"response": true,
     "ack_shot": {
       "x": 9,
       "y": 2,
       "isHit": true,
       "isSunk": true,
       "isWin": false,
       "ship": [9,2,9,3,9,4]}, // coordinates of the sunken ship
     "shot": { ... }}*/
?>
