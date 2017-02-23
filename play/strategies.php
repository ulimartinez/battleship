<?php
function shootRandom(){
  global $game;
  $x;
  $y;
  do {
    $x = rand(0,9);
    $y = rand(0,9);
    $valid = call_user_func_array(array($game,"shotIsValid"), array($x, $y));
  } while (!$valid);

  return doShot($x, $y);
}
function shootSmart(){
  global $game;
  echo "Yay a smart shot";
}
function shootSweep(){
  global $game;
  for($i = 0; $i < 10; $i++){
    for($j = 0; $j < 10; $i++){
      if($game->shotIsValid($i, $j)){
        return doShot($i, $j);
        break 2;
      }
    }
  }
}
function doShot($x, $y){
  global $game;
  $hitShip = $game->hitShip($x, $y);
  return $game->buildResponse($x, $y, $hitShip);
}
?>
