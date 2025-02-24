<?php
session_start();
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<?php

require_once("Ship.php");
require_once("Player.php");

$db = new PDO("mysql:host=localhost;dbname=ShipFight",'root','');

$p_query = "select name, ammo from Player";
$p_result = $db->query($p_query);

$s_query = "select name, location, hp from Ship";
$s_result = $db->query($s_query);

$p_row = $p_result->fetch(PDO::FETCH_ASSOC);
$s_row = $s_result->fetch(PDO::FETCH_ASSOC);

$player = new Player($p_row['name'],$p_row['ammo']);
$ship = new Ship();
$ship->setName($s_row['name']);

$query = "delete from Player";
$num_row = $db->exec($query);
$query_ship = "delete from Ship";
$ship_num_row = $db->exec($query_ship);

if ($num_row > 0 && $ship_num_row){
    echo "Вітаю. <b>{$player->getName()}</b>, ви виграли та потопили <b>{$ship->getName()}</b> за <b>{$player->getAmmo()}</b> хід";
}

echo "<table border='2'><tr>";
for($i = 1; $i <= 10; $i++){
    echo "<td>$i</td>";
}
echo "</tr>";

for ($i = 0; $i < 10; $i++){
    $status = 0;
    for($j = 0; $j < count($_SESSION['shoot']); $j++){
        $status_2 = 0;
        for ($k = 0; $k < count($_SESSION['ship_loc']); $k++){
            if ($i == $_SESSION['ship_loc'][$k]-1 && $i == $_SESSION['shoot'][$j]-1){
                echo "<td>X</td>";
                $status_2 = 1;
                $status = 1;
            }
        }
        if ($i == $_SESSION['shoot'][$j]-1 && $status_2 != 1){
            echo "<td>O</td>";
            $status = 1;
        }
    }
    if ($status != 1){
        echo "<td>__</td>";
    }

}

echo "</table>";



$_SESSION['ship_loc'] = [];
$_SESSION['shoot'] = [];

session_destroy();

?>
</body>
</html>