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
  $hitShip = $game->hitShip($x, $y);
  return $game->buildResponse($x, $y, $hitShip);
}
function shootSmart(){
  global $game;
  echo "Yay a smart shot";
}
function shootSweep(){
  echo "Yay a sweep shot";
}
?>
