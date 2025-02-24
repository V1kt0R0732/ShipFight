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

$db = new PDO("mysql:hostname=localhost;dbname=ShipFight",'root','');

if (!isset($_POST['send'])){
?>
    <form method="post" action="ShipFight2.0.php">
        <h2>Введіть свій NickName:</h2>
        <input type="text" name="player" placeholder="Nick Name">
        <input type="submit" name="send" value="Почати Гру">
    </form>
<?php
}
elseif(isset($_POST['send'], $_POST['player']) && !empty($_POST['player'])){

    $query = "delete from Player";
    $num_row = $db->exec($query);
    $query_ship = "delete from Ship";
    $ship_num_row = $db->exec($query_ship);

    $player = new Player($_POST['player'],0);
    $ship = new Ship();

    $type = $ship->create_2();
    $ship->save_2($db, $type);

    $player->save($db);

    if($type == "v"){
        $_SESSION['h_loc'] = [];
        $_SESSION['v_loc'] = $ship->getLocation_v();
        $_SESSION['h_loc'][] = $ship->getLocation_h();
    }
    elseif($type == "h"){
        $_SESSION['v_loc'] = [];
        $_SESSION['h_loc'] = $ship->getLocation_h();
        $_SESSION['v_loc'][] = $ship->getLocation_v();
    }

    $_SESSION['v_shoot'] = [];
    $_SESSION['h_shoot'] = [];

    header("location:battle_2.php");



}
else{
    echo "Error";
}
?>
</body>
</html>