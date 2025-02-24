<?php
session_start();
?><!doctype html>
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

$p_query = "select name, ammo from Player";
$p_result = $db->query($p_query);
$p_row = $p_result->fetch(PDO::FETCH_ASSOC);


$s_query = "select name, location_v, location_h, hp from Ship limit 1";
$s_result = $db->query($s_query);
$ship = new Ship();


$player = new Player($p_row['name'],$p_row['ammo']);

echo $player->show()."<br>";


//////////////////////////////
/*          Вивід           */

echo "<table border='2'>";
echo "<tr><th>В\Г</th>";

for($i = 1; $i <= 10; $i++){
    echo "<th>$i</th>";
}
echo "</tr>";

for ($i = 0; $i < 10; $i++){
    echo "<tr><th>". $i+1 ."</th>";
    for ($j = 0; $j < 10; $j++){
        $ship_status = 0;
        for($k = 0; $k < count($_SESSION['v_shoot']); $k++){
            $status = 0;
            for($y = 0; $y < count($_SESSION['location_v']); $y++) {
                if($i+1 == $_SESSION['v_shoot'][$k] && $j+1 == $_SESSION['h_shoot'][$k] && $_SESSION['v_shoot'][$k] == $_SESSION['location_v'][$y] && $_SESSION['h_shoot'][$k] == $_SESSION['location_h'][$y]){
                    echo "<td>X</td>";
                    $status = 1;
                    $ship_status = 1;
                }
            }
            if ($i + 1 == $_SESSION['v_shoot'][$k] && $j + 1 == $_SESSION['h_shoot'][$k] && $status != 1) {
                echo "<td>O</td>";
                $ship_status = 1;
            }
        }
        if($ship_status != 1){
        echo "<td>__</td>";
        }
    }
    echo "</tr>";
}

echo "</table>";
//////////////////////////////



if(!isset($_POST['send'])){
    ?>
    <form action="battle_3.php" method="post">
        <h4>Оберіть клітинку для пострілу:</h4>
        <b>Horizontal</b>
        <input type="number" name="horizontal" min="1" max="10"><br>
        <b>Vertical</b>
        <input type="number" name="vertical" min="1" max="10"><br>
        <input type="submit" name="send" value="Shoot">
    </form>
    <?php
}
elseif (isset($_POST['send'], $_POST['horizontal'], $_POST['vertical']) && !empty($_POST['vertical']) && !empty($_POST['horizontal'])){

    /* Вивід пострілу користувача */
    $shoot_status = 0;

    for ($i = 0; $i < count($_SESSION['v_shoot']); $i++){
        if ($_SESSION['v_shoot'][$i] == $_POST['vertical'] && $_SESSION['h_shoot'][$i] == $_POST['horizontal']){
            $shoot_status = 1;
        }
    }

    if ($shoot_status != 1){
        $_SESSION['v_shoot'][] = $_POST['vertical'];
        $_SESSION['h_shoot'][] = $_POST['horizontal'];
    }

    $shoot = [$_POST['vertical'], $_POST['horizontal']];

    /* ////////////////////////// */

    $result = $ship->battle($player, $shoot);

    /* ///////////////////////// */

    if($result != "Перемога") {
        echo "Результат пострілу: <b>$result</b>";
        header("refresh:2;url=battle_3.php");
    }
    else{
        header("location:final_2.php");
    }


}
//elseif($ship->getHp() == 0){
//

//
//}

?>
</body>
</html>