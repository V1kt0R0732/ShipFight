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
    <form method="post" action="ShipFight3.0.php">
        <h2>Введіть свій NickName:</h2>
        <input type="text" name="player" placeholder="Nick Name">
        <input type="submit" name="send" value="Почати Гру">
    </form>
    <?php
}
elseif(isset($_POST['send'], $_POST['player']) && !empty($_POST['player'])){

    $db = new PDO("mysql:hostname=localhost;dbname=ShipFight",'root','');

    $query = "delete from Player";
    $num_row = $db->exec($query);
    $query_ship = "delete from Ship";
    $ship_num_row = $db->exec($query_ship);


    $ship = new Ship();
    $player = new Player($_POST['player'],0);
    $player->save($db);


    $type = $ship->create_3(4);
    $ship->setName("Squad");
    $ship->save_2($db, $type);
    echo "<br>";

    for($i = 0; $i < 3; $i++){
        $type = $ship->create_3(2);
        $ship->setName("Double_$i");
        $ship->save_2($db, $type);
        echo "<br>";
    }
    for($i = 0; $i < 2; $i++){
        $type = $ship->create_3(3);
        $ship->setName("Triple_$i");
        $ship->save_2($db, $type);
        echo "<br>";
    }
    for($i = 0; $i < 4; $i++){
        $type = $ship->create_3(1);
        $ship->setName("Solo_$i");
        $ship->save_2($db, $type);
        echo "<br>";
    }

    $_SESSION['location_v'] = [];
    $_SESSION['location_h'] = [];


    $query = "select location_v, location_h, type from Ship";
    $result = $db->query($query);
    while($row = $result->fetch(PDO::FETCH_ASSOC)){

        if($row['type'] == 'v'){
            $arr = explode(' ',$row['location_v']);
            for($i = 0; $i < count($arr); $i++){
                $_SESSION['location_v'][] = $arr[$i];
                $_SESSION['location_h'][] = $row['location_h'];
            }
        }
        elseif($row['type'] == 'h'){
            $arr = explode(' ',$row['location_h']);
            for($i = 0; $i < count($arr); $i++){
                $_SESSION['location_v'][] = $row['location_v'];
                $_SESSION['location_h'][] = $arr[$i];
            }
        }
        elseif($row['type'] == 'solo'){
            $_SESSION['location_h'][] = $row['location_h'];
            $_SESSION['location_v'][] = $row['location_v'];
        }

    }

//    echo "<br>";
//    print_r($_SESSION['location_h']);
//    echo "<br>";
//    print_r($_SESSION['location_v']);

    $_SESSION['v_shoot'] = [];
    $_SESSION['h_shoot'] = [];

    header("location:battle_3.php");


}
else{
    echo "Error";
}
?>
</body>
</html>