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
  echo "$x,$y";
}
function shootSmart(){
  global $game;
  echo "Yay a smart shot";
}
function shootSweep(){
  echo "Yay a sweep shot";
}
?>
