/**
 *@author Elsa Gonzalez
 *@author Pedro Barragan
 *@author Ulises Martinez
 *this section was automatically inserted sing a sh script
 */
<?php
//use class\Info as Info;
require '../class/game.class.php';
$game_info = new Game();
header('Content-Type: application/json');
echo $game_info->getInfoJson();
?>
