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
  $cheat = rand(0,1);
  if($cheat){
    //do some cheeky stuff here
    foreach ($game->shipPlacements as $shipPlacement) {
      if($shipPlacement->isHorizontal){
        for($i = $shipPlacement->getX(); $i < $shipPlacement->getX()+$shipPlacement->getShip()->getSize(); $i++){
          if($game->shotIsValid($i, $shipPlacement->getY())){
            return doShot($i, $shipPlacement->getY());
            break 2;
          }
        }
      }
      else{
        for($i = $shipPlacement->getY(); $i < $shipPlacement->getY()+$shipPlacement->getShip()->getSize(); $i++){
          if($game->shotIsValid($shipPlacement->getX(), $i)){
            return doShot($shipPlacement->getX(), $i);
            break 2;
          }
        }
      }
    }
  }
  else{
    return shootRandom();
  }
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
  global $pid;
  $hitShip = $game->hitShip($x, $y);
  return $game->buildResponse($x, $y, $hitShip, $pid);
}
?>
