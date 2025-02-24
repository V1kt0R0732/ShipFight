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
<h2>Бій Триває</h2>
<?php

require_once("Player.php");
require_once("Ship.php");

$db = new PDO("mysql:host=localhost;dbname=ShipFight",'root','');

$p_query = "select name, ammo from Player";
$p_result = $db->query($p_query);

$s_query = "select name, location, hp from Ship";
$s_result = $db->query($s_query);

$p_row = $p_result->fetch(PDO::FETCH_ASSOC);
$s_row = $s_result->fetch(PDO::FETCH_ASSOC);

$player = new Player($p_row['name'], $p_row['ammo']);
$ship = new Ship();

$ship->setName($s_row['name']);
$ship->setHp($s_row['hp']);
$ship->setLocation($s_row['location']);

echo $player->show()."<br>";
echo $ship->show();

$loc = $ship->getLocation();
$ammo = $player->getAmmo();



$shoot = 5;


/* ////////// */

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

if (!isset($_POST['send'], $_POST['shoot'])){
?>
    <form action="battle.php" method="post">
        <b>Оберіть поле для вистрілу</b><br>
        <input type="number" name="shoot" min="1" max="10">
        <input type="submit" name="send" value="Shoot">
    </form>
<?php

}

elseif(isset($_POST['send'], $_POST['shoot']) && !empty($_POST['shoot'])){

    $result = $ship->battle($player, $_POST['shoot']);

    if (isset($_POST['shoot']) && !empty($_POST['shoot'])){
        $newStatus = 0;
        for ($i = 0; $i < count($_SESSION['shoot']); $i++){
            if($_SESSION['shoot'][$i] == $_POST['shoot']){
                $newStatus = 1;
            }
        }
        if ($newStatus != 1){
            $_SESSION['shoot'][] = $_POST['shoot'];
        }
    }

    if ($result == 'Потоплено'){

        header("location:final.php");

    }
    else{

        echo "Результат пострілу: <b>$result</b>";
        header("refresh:3;url=battle.php");

    }


}


?>
</body>
</html>