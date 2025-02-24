<?php

require_once("Ship.php");


$db = new PDO("mysql:hostname=localhost;dbname=ShipFight",'root','');

$query = "delete from Player";
$num_row = $db->exec($query);
$query_ship = "delete from Ship";
$ship_num_row = $db->exec($query_ship);


$ship = new Ship();


//
//$type = $ship->create_3(2);
//$ship->save_2($db, $type);

$type = $ship->create_3(4);
$ship->save_2($db, $type);
echo "<br>";


for($i = 0; $i < 3; $i++){
    $type = $ship->create_3(2);
    $ship->save_2($db, $type);
    echo "<br>";
}
for($i = 0; $i < 2; $i++){
    $type = $ship->create_3(3);
    $ship->save_2($db, $type);
    echo "<br>";
}

for($i = 0; $i < 4; $i++){
    $type = $ship->create_3(1);
    $ship->save_2($db, $type);
    echo "<br>";
}








?>