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

$s_query = "select name, location_v, location_h, hp from Ship";
$s_result = $db->query($s_query);

$p_row = $p_result->fetch(PDO::FETCH_ASSOC);
$s_row = $s_result->fetch(PDO::FETCH_ASSOC);

$player = new Player($p_row['name'],$p_row['ammo']);
$ship = new Ship();



$ship->setName($s_row['name']);
$ship->setHp($s_row['hp']);
$ship->setLocation_v($s_row['location_v']);
$ship->setLocation_h($s_row['location_h']);

echo $player->show()."<br>";
echo $ship->show();


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
        $h_status = 0;
        for($k = 0; $k < count($_SESSION['v_shoot']); $k++){
            $sec_status = 0;
            if(count($_SESSION['v_loc']) > count($_SESSION['h_loc'])){
                for($h = 0; $h < count($_SESSION['v_loc']); $h++){
                    if ($i == $_SESSION['v_shoot'][$k]-1 && $j == $_SESSION['h_shoot'][$k]-1 && $i == $_SESSION['v_loc'][$h]-1 && $j == $_SESSION['h_loc'][0]-1){
                        echo "<td>X</td>";
                        $sec_status = 1;
                        $h_status = 1;
                    }
                }
            }
            else{
                for($h = 0; $h < count($_SESSION['h_loc']); $h++){
                    if ($i == $_SESSION['v_shoot'][$k]-1 && $j == $_SESSION['h_shoot'][$k]-1 && $j == $_SESSION['h_loc'][$h]-1 && $i == $_SESSION['v_loc'][0]-1){
                        echo "<td>X</td>";
                        $sec_status = 1;
                        $h_status = 1;
                    }
                }
            }

            if ($i == $_SESSION['v_shoot'][$k]-1 && $j == $_SESSION['h_shoot'][$k]-1 && $sec_status != 1){
                echo "<td>O</td>";
                $h_status = 1;
            }
        }
        if($h_status != 1){
            echo "<td>__</td>";
        }
    }
    echo "</tr>";
}

echo "</table>";
//////////////////////////////



if(!isset($_POST['send']) && $ship->getHp() != 0){
?>
    <form action="battle_2.php" method="post">
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
    /* ////////////////////////// */

    $shoot = [$_POST['vertical'], $_POST['horizontal']];

    $result = $ship->battle($player, $shoot);


    echo "Результат пострілу: <b>$result</b>";
    header("refresh:2;url=battle_2.php");



}
elseif($ship->getHp() == 0){

    echo "Вітаю З перемогою!!!";
    echo "<br><b>Гру завершершено, всі кораблі потоплено.</b><br>";
    echo "<b><a href='ShipFight2.0.php'>З початку</a></b>";

    $_SESSION['ship_loc'] = [];
    $_SESSION['shoot'] = [];

    session_destroy();

}

?>
</body>
</html>