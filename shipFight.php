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

if (!isset($_POST['send'])){
?>
<form action="shipFight.php" method="post">
    <h3>Приготуйтеся до Бою</h3>
    <input type="text" name="player_name" placeholder="Ім'я Гравця">
    <br>
    <input type="submit" name="send" value="До Бою">
</form>
<?php
}
elseif(isset($_POST['send'], $_POST['player_name']) && !empty($_POST['player_name'])){

    $query = "delete from Player";
    $num_row = $db->exec($query);
    $query_ship = "delete from Ship";
    $ship_num_row = $db->exec($query_ship);

    $ship_1 = new Ship();
    $player_1 = new Player($_POST['player_name'],0);


    $ship_1->create();
    $ship_1->save($db);

    $_SESSION['ship_loc'] = $ship_1->getLocation();
    $_SESSION['shoot'] = [];

    $player_1->save($db);

    header("location:battle.php");

}



?>
</body>
</html>