<?php
//use class\Info as Info;
require '../class/game.class.php';
$game_info = new Game();
header('Content-Type: application/json');
echo $game_info->getInfoJson();
?>
