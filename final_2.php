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

    $p_query = "select name, ammo from Player";
    $p_result = $db->query($p_query);
    $p_row = $p_result->fetch(PDO::FETCH_ASSOC);

    $player = new Player($p_row['name'],$p_row['ammo']);

    echo $player->show()."<br>";

echo "<br>";
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
echo "Вітаю З перемогою!!!";
echo "<br><b>Гру завершершено, всі кораблі потоплено. Таблиця лідерів обновлена</b><br><b>Зроблено пострілів: </b>".$player->getAmmo()."<br>";
echo "<h4>Таблиця лідерів:</h4>";


    $query_check = "select name, id, shoots from leaders where name = '{$player->getName()}'";
    $result_check = $db->query($query_check);
    $row_check = $result_check->fetch(PDO::FETCH_ASSOC);

    if(empty($row_check)){
        $query_lead_add = "insert into leaders (name, shoots) values ('{$player->getName()}',{$player->getAmmo()})";
        $db->query($query_lead_add);
    }
    else{
        if($row_check['shoots'] > $player->getAmmo()){
            $query_up = "update leaders set shoots = {$player->getAmmo()} where id = {$row_check['id']}";
            $db->query($query_up);
        }
    }

    $query_lead = "select name, shoots from leaders order by shoots asc limit 3";
    $result_lead = $db->query($query_lead);


    $num = 1;
    echo "<table border='2'><tr><th>№</th><th>Name</th><th>Shoots</th></tr>";

    while ($row_lead = $result_lead->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr><td>$num</td><td>{$row_lead['name']}</td><td>{$row_lead['shoots']}</td></tr>";
        $num++;
    }
    echo "</table>";

echo "<b><a href='index.php'>З початку</a></b>";


$_SESSION['ship_loc'] = [];
$_SESSION['shoot'] = [];

session_destroy();


?>
</body>
</html>