<?php

class Ship
{
    private $name;
    private $location;
    private $location_h;
    private $location_v;
    private $hp;


    public function show(){
        return "Корабль має ".$this->hp." HP. Його назва: <b>".$this->getName()."</b>";
    }
    public function battle($player, $shoot){

        $db = new PDO("mysql:host=localhost;dbname=ShipFight",'root','');

        $place = $player->shoot($shoot);


        $query_ship = "select location_v, location_h, type, name, hp from ship";
        $result_ship = $db->query($query_ship);

        $loc = ['H'=>[],'V'=>[],'name'=>[],'hp'=>[]];
        while($row = $result_ship->fetch(PDO::FETCH_ASSOC)){

            if($row['type'] == 'v'){
                $arr = explode(' ',$row['location_v']);

                for($i = 0; $i < count($arr); $i++){
                    $loc['V'][] = $arr[$i];
                    $loc['H'][] = $row['location_h'];
                    $loc['name'][] = $row['name'];
                    $loc['hp'][] = $row['hp'];
                }

            }
            elseif($row['type'] == 'h'){
                $arr = explode(' ',$row['location_h']);

                for($i = 0; $i < count($arr); $i++){
                    $loc['V'][] = $row['location_v'];
                    $loc['H'][] = $arr[$i];
                    $loc['name'][] = $row['name'];
                    $loc['hp'][] = $row['hp'];
                }
            }

            elseif($row['type'] == 'solo'){
                $loc['V'][] = $row['location_v'];
                $loc['H'][] = $row['location_h'];
                $loc['name'][] = $row['name'];
                $loc['hp'][] = $row['hp'];
            }

        }

        $result = "Промах";

        //print_r($loc);

        for($i = 0; $i < count($loc['H']); $i++){
            if($loc['V'][$i] == $place[0] && $loc['H'][$i] == $place[1]){
                $query = "select location_v, location_h, type from Ship where name = '{$loc['name'][$i]}'";
                $result = $db->query($query);
                $row = $result->fetch(PDO::FETCH_ASSOC);

                if($row['type'] == 'v'){
                    $arr = explode(' ', $row['location_v']);
                    for($j = 0; $j < count($arr); $j++){
                        if($place[0] == $arr[$j]){
                            $arr[$j] = -1;
                            $v_loc = implode(' ',$arr);
                            $h_loc = $row['location_h'];
                            break;
                        }
                    }
                }
                elseif($row['type'] == 'h'){
                    $arr = explode(' ',$row['location_h']);
                    for($j = 0; $j < count($arr); $j++){
                        if($place[1] == $arr[$j]){
                            $arr[$j] = -1;
                            $h_loc = implode(' ',$arr);
                            $v_loc = $row['location_v'];
                            break;
                        }
                    }
                }
                elseif($row['type'] == 'solo'){
                    $v_loc = -1;
                    $h_loc = -1;
                }

                $query = "update Ship set hp=(hp-1), location_v = '$v_loc', location_h = '$h_loc' where name = '{$loc['name'][$i]}'";
                $db->query($query);

                $this->hp = $loc['hp'][$i]-1;
                $result = "Попав";

                if($this->hp == 0){
                    $result = "Потоплено";
                    $query_dell = "delete from Ship where name = '{$loc['name'][$i]}'";
                    $db->exec($query_dell);

                    $query_check = "select id from Ship";
                    $result_check = $db->query($query_check);
                    $row = $result_check->fetch(PDO::FETCH_ASSOC);

                    if(empty($row)){
                        $result = "Перемога";
                    }
                }
            }
        }



        /*

        for ($i = 0; $i < count($this->location_v); $i++){
            for($j = 0; $j < count($this->location_h); $j++){
                if ($this->location_v[$i] == $place[0] && $this->location_h[$j] == $place[1]){
                    $result = "Попав";
                    $this->hp--;

                    $db = new PDO("mysql:host=localhost;dbname=ShipFight",'root','');

                    if (count($this->location_v) > count($this->location_h)){
                        $this->location_v[$i] = -1;
                    }
                    else {
                        $this->location_h[$j] = -1;
                    }

                    $loc_h = implode(' ', $this->location_h);
                    $loc_v = implode(' ', $this->location_v);

                    $query = "update Ship set hp=hp-1, location_h='{$loc_h}', location_v='{$loc_v}'";

                    $db->query($query);

                    break;
                }
            }
        }

        */


//        for($i = 0; $i < count($this->location);$i++){
//            if($this->location[$i] == $place){
//                $result = "Попав";
//                $this->hp--;
//
//                $db = new PDO("mysql:host=localhost;dbname=ShipFight",'root','');
//
//                $this->location[$i] = -1;
//
//                $loc = implode(' ',$this->location);
//
//                $query = "update Ship set hp=hp-1, location='{$loc}'";
//
//                $db->query($query);
//
//
//                break;
//            }
//        }

        /*
        for($i = 0; $i < 3; $i++){
            if($place == $this->location[$i]){
                $result = "Попав";
            }
        }

        if (empty($result)){
            $result = "Промах";
        }
        */

        return $result;

    }

    public function create_2($height){

        if ($height == 1){
            $pos = 3;
        }
        else {
            $pos = rand(1, 2);
        }

        $this->hp = $height;

        switch ($height) {
            case 1:
                $this->name = "Solo";
                $rand = rand(1, 10);
                break;
            case 2:
                $this->name = "Double";
                $rand = rand(1, 9);
                break;
            case 3:
                $this->name = "Triple";
                $rand = rand(1, 8);
                break;
            case 4:
                $this->name = "Squad";
                $rand = rand(1, 7);
                break;
        }

        $rand_2 = rand(1, 10);

        if ($pos == 1) {

            $this->location_v = "$rand_2";

            for ($i = 0; $i < $height; $i++) {
                $this->location_h[$i] = $rand;
                $rand++;
            }

            return "h";
        }
        elseif($pos == 2){

            $this->location_h = "$rand_2";

            for($i = 0; $i < $height; $i++){
                $this->location_v[$i] = $rand;
                $rand++;
            }

            return "v";

        }
        else{

            $this->location_h = "$rand";
            $this->location_v = "$rand_2";
            return "solo";
        }

    }

    public function create_3($height){

        $this->hp = $height;

        $db = new PDO("mysql:hostname=localhost;dbname=ShipFight",'root','');
        $query = "select location_v, location_h, name, type from Ship";
        $result = $db->query($query);

        $lock = ['H'=>[],'V'=>[]];


        while($row = $result->fetch(PDO::FETCH_ASSOC)){


            if ($row['type'] == "h"){

                $arr = explode(' ', $row['location_h']);

                for($i = 0; $i < count($arr); $i++){

                    if($i == 0 && $arr[$i] != 1){
                        $lock['H'][] = $arr[$i]-1;
                        $lock['V'][] = $row['location_v'];

                        $lock['H'][] = $arr[$i]-1;
                        $lock['V'][] = $row['location_v']+1;

                        $lock['H'][] = $arr[$i]-1;
                        $lock['V'][] = $row['location_v']-1;
                    }

                    $lock['H'][] = $arr[$i];
                    $lock['V'][] = $row['location_v']+1;

                    $lock['H'][] = $arr[$i];
                    $lock['V'][] = $row['location_v'];

                    $lock['H'][] = $arr[$i];
                    $lock['V'][] = $row['location_v']-1;

                    if($i == count($arr)-1 && $arr[$i] != 10){
                        $lock['H'][] = $arr[$i]+1;
                        $lock['V'][] = $row['location_v'];

                        $lock['H'][] = $arr[$i]+1;
                        $lock['V'][] = $row['location_v']+1;

                        $lock['H'][] = $arr[$i]+1;
                        $lock['V'][] = $row['location_v']-1;
                    }

                }
                /*

                $arr = explode(' ', $row['location_h']);

                for($i = 0; $i < count($arr); $i++){

                    $lock_status = 0; // Совпадений нет
                    $lock_status_min = 0;
                    $lock_status_max = 0;

                    for($j = 0; $j < count($lock['H']); $j++){
                        if($arr[$i] == $lock['H'][$j]){
                            $lock_status = 1; // Есть совпадение в масиве
                        }
                        if($arr[$i] != 1 && $i == 0 && $arr[$i]-1 == $lock['H'][$j]){
                            $lock_status_min = 1;
                        }
                        if($arr[$i] != 10 && $i == count($arr)-1 && $arr[$i]+1 == $lock['H'][$j]){
                            $lock_status_max = 1;
                        }
                    }

                    if($lock_status == 0){
                        $lock['H'][] = $arr[$i];
                    }
                    if($i == 0 && $arr[$i] != 1 && $lock_status_min == 0){
                        $lock['H'][] = $arr[$i]-1;
                    }
                    if($i == count($arr)-1 && $arr[$i] != 10 && $lock_status_max == 0){
                        $lock['H'][] = $arr[$i]+1;
                    }

                }

                $lock_status = 0;
                $lock_status_max = 0;
                $lock_status_min = 0;

                for($j = 0; $j < count($lock['V']); $j++){
                    if($lock['V'][$j] == $row['location_v']){
                        $lock_status = 1;
                    }
                    if($row['location_v'] != 1 && $row['location_v']-1 == $lock['V'][$j]){
                        $lock_status_min = 1;
                    }
                    if($row['location_v'] != 10 && $row['location_v']+1 == $lock['V'][$j]){
                        $lock_status_max = 1;
                    }
                }

                if($lock_status == 0) {
                    $lock['V'][] = $row['location_v'];
                }
                if($row['location_v'] != 1 && $lock_status_min == 0){
                    $lock['V'][] = $row['location_v'] - 1;
                }
                if($row['location_v'] != 10 && $lock_status_max == 0){
                    $lock['V'][] = $row['location_v'] + 1;
                }

                */
            }

            if($row['type'] == "v"){

                $arr = explode(' ', $row['location_v']);

                for($i = 0; $i < count($arr); $i++){

                    if($i == 0 && $arr[$i] != 1){

                        $lock['V'][] = $arr[$i]-1;
                        $lock['H'][] = $row['location_h']+1;

                        $lock['V'][] = $arr[$i]-1;
                        $lock['H'][] = $row['location_h'];

                        $lock['V'][] = $arr[$i]-1;
                        $lock['H'][] = $row['location_h']-1;

                    }

                    $lock['V'][] = $arr[$i];
                    $lock['H'][] = $row['location_h']+1;

                    $lock['V'][] = $arr[$i];
                    $lock['H'][] = $row['location_h'];

                    $lock['V'][] = $arr[$i];
                    $lock['H'][] = $row['location_h']-1;

                    if($i == count($arr)-1 && $arr[$i] != 10){

                        $lock['V'][] = $arr[$i]+1;
                        $lock['H'][] = $row['location_h']+1;

                        $lock['V'][] = $arr[$i]+1;
                        $lock['H'][] = $row['location_h'];

                        $lock['V'][] = $arr[$i]+1;
                        $lock['H'][] = $row['location_h']-1;

                    }
                }
                /*
                $arr = explode(' ', $row['location_v']);

                for($i = 0; $i< count($arr); $i++){

                    $lock_status = 0; // Совпадений нет
                    $lock_status_min = 0;
                    $lock_status_max = 0;

                    for($j = 0; $j < count($lock['V']); $j++){
                        if($arr[$i] == $lock['V'][$j]){
                            $lock_status = 1; // Есть совпадение в масиве
                        }
                        if($arr[$i] != 1 && $i == 0 && $arr[$i]-1 == $lock['V'][$j]){
                            $lock_status_min = 1;
                        }
                        if($arr[$i] != 10 && $i == count($arr)-1 && $arr[$i]+1 == $lock['V'][$j]){
                            $lock_status_max = 1;
                        }
                    }

                    if($lock_status == 0){
                        $lock['V'][] = $arr[$i];
                    }
                    if($i == 0 && $arr[$i] != 1 && $lock_status_min == 0){
                        $lock['V'][] = $arr[$i]-1;
                    }
                    if($i == count($arr)-1 && $arr[$i] != 10 && $lock_status_max == 0){
                        $lock['V'][] = $arr[$i]+1;
                    }

                }

                $lock_status = 0;
                $lock_status_max = 0;
                $lock_status_min = 0;

                for($j = 0; $j < count($lock['H']); $j++){
                    if($lock['H'][$j] == $row['location_h']){
                        $lock_status = 1;
                    }
                    if($row['location_h'] != 1 && $row['location_h']-1 == $lock['H'][$j]){
                        $lock_status_min = 1;
                    }
                    if($row['location_h'] != 10 && $row['location_h']+1 == $lock['H'][$j]){
                        $lock_status_max = 1;
                    }
                }

                if($lock_status == 0) {
                    $lock['H'][] = $row['location_h'];
                }
                if($row['location_h'] != 1 && $lock_status_min == 0){
                    $lock['H'][] = $row['location_h'] - 1;
                }
                if($row['location_h'] != 10 && $lock_status_max == 0){
                    $lock['H'][] = $row['location_h'] + 1;
                }

                */
            }
            elseif($row['type'] == 'solo'){

                if($row['location_v'] == 1 && $row['location_h'] == 1){ // Лівий верхній угол
                    $lock['V'][] = $row['location_v']+1;
                    $lock['H'][] = $row['location_h']+1;

                    $lock['V'][] = $row['location_v'];
                    $lock['H'][] = $row['location_h']+1;

                    $lock['V'][] = $row['location_v']+1;
                    $lock['H'][] = $row['location_h'];
                }
                elseif($row['location_v'] == 1 && $row['location_h'] == 10){ // Правий верхній угол
                    $lock['V'][] = $row['location_v']+1;
                    $lock['H'][] = $row['location_h']-1;

                    $lock['V'][] = $row['location_v'];
                    $lock['H'][] = $row['location_h']-1;

                    $lock['V'][] = $row['location_v']+1;
                    $lock['H'][] = $row['location_h'];
                }
                elseif($row['location_v'] == 10 && $row['location_h'] == 10){ // Правий нижній Кут
                    $lock['V'][] = $row['location_v']-1;
                    $lock['H'][] = $row['location_h']-1;

                    $lock['V'][] = $row['location_v'];
                    $lock['H'][] = $row['location_h']-1;

                    $lock['V'][] = $row['location_v']-1;
                    $lock['H'][] = $row['location_h'];
                }
                elseif($row['location_v'] == 10 && $row['location_h'] == 1){
                    $lock['V'][] = $row['location_v']-1;
                    $lock['H'][] = $row['location_h']+1;

                    $lock['V'][] = $row['location_v']-1;
                    $lock['H'][] = $row['location_h'];

                    $lock['V'][] = $row['location_v'];
                    $lock['H'][] = $row['location_h']+1;
                }
                elseif($row['location_v'] == 1 && $row['location_h'] != 1){ // Під верхньою стінкою
                    $lock['V'][] = $row['location_v'];
                    $lock['H'][] = $row['location_h']-1;

                    $lock['V'][] = $row['location_v'];
                    $lock['H'][] = $row['location_h']+1;

                    $lock['V'][] = $row['location_v']+1;
                    $lock['H'][] = $row['location_h'];

                    $lock['V'][] = $row['location_v']+1;
                    $lock['H'][] = $row['location_h']+1;

                    $lock['V'][] = $row['location_v']+1;
                    $lock['H'][] = $row['location_h']-1;
                }
                elseif($row['location_v'] != 10 && $row['location_h'] == 10){ // Права стінка
                    $lock['V'][] = $row['location_v']+1;
                    $lock['H'][] = $row['location_h'];

                    $lock['V'][] = $row['location_v']-1;
                    $lock['H'][] = $row['location_h'];

                    $lock['V'][] = $row['location_v']+1;
                    $lock['H'][] = $row['location_h']-1;

                    $lock['V'][] = $row['location_v'];
                    $lock['H'][] = $row['location_h']-1;

                    $lock['V'][] = $row['location_v']-1;
                    $lock['H'][] = $row['location_h']-1;

                }
                elseif($row['location_v'] == 10 && $row['location_h'] != 10){
                    $lock['V'][] = $row['location_v'];
                    $lock['H'][] = $row['location_h']+1;

                    $lock['V'][] = $row['location_v'];
                    $lock['H'][] = $row['location_h']-1;

                    $lock['V'][] = $row['location_v']-1;
                    $lock['H'][] = $row['location_h'];

                    $lock['V'][] = $row['location_v']-1;
                    $lock['H'][] = $row['location_h']-1;

                    $lock['V'][] = $row['location_v']-1;
                    $lock['H'][] = $row['location_h']+1;
                }
                elseif($row['location_v'] != 1 && $row['location_h'] == 1){
                    $lock['V'][] = $row['location_v']+1;
                    $lock['H'][] = $row['location_h'];

                    $lock['V'][] = $row['location_v']-1;
                    $lock['H'][] = $row['location_h'];

                    $lock['V'][] = $row['location_v'];
                    $lock['H'][] = $row['location_h']+1;

                    $lock['V'][] = $row['location_v']+1;
                    $lock['H'][] = $row['location_h']+1;

                    $lock['V'][] = $row['location_v']-1;
                    $lock['H'][] = $row['location_h']+1;
                }
                else{

                    $lock['V'][] = $row['location_v']+1;
                    $lock['H'][] = $row['location_h'];

                    $lock['V'][] = $row['location_v']-1;
                    $lock['H'][] = $row['location_h'];

                    $lock['V'][] = $row['location_v'];
                    $lock['H'][] = $row['location_h']+1;

                    $lock['V'][] = $row['location_v'];
                    $lock['H'][] = $row['location_h']-1;

                    $lock['V'][] = $row['location_v']-1;
                    $lock['H'][] = $row['location_h']-1;

                    $lock['V'][] = $row['location_v']-1;
                    $lock['H'][] = $row['location_h']+1;

                    $lock['V'][] = $row['location_v']+1;
                    $lock['H'][] = $row['location_h']+1;

                    $lock['V'][] = $row['location_v']+1;
                    $lock['H'][] = $row['location_h']-1;
                }

                $lock['V'][] = $row['location_v'];
                $lock['H'][] = $row['location_h'];


            }

        }

//        echo "<br>Lock:<br>";
//        print_r($lock['H']);
//        echo "<br>";
//        print_r($lock['V']);

        if($height == 1){
            $type = 3; // 3- Solo

            //$this->name = "Solo";

            do{
                $status = 0;

                $x = rand(1, 10);
                $y = rand(1, 10);

                for ($i = 0; $i < count($lock['V']); $i++) {
                    if($x == $lock['H'][$i] && $y == $lock['V'][$i]){
                        $status = 1;
                    }
                }

            }while($status == 1);

        }
        else {

//            switch ($height) {
//                case 2:
//                    $this->name = "Double";
//                    break;
//                case 3:
//                    $this->name = "Triple";
//                    break;
//                case 4:
//                    $this->name = "Squad";
//                    break;
//            }

            do {
                $type = rand(1, 2); // 1 - Hor | 2 - Ver

                $status = 0;

                if ($type == 1) {
                    switch ($height) {
                        case 2:
                            $x = rand(1, 9);
                            break;
                        case 3:
                            $x = rand(1, 8);
                            break;
                        case 4:
                            $x = rand(1, 7);
                            break;
                    }
                    $y = rand(1, 10);
                } else {
                    switch ($height) {
                        case 2:
                            $y = rand(1, 9);
                            break;
                        case 3:
                            $y = rand(1, 8);
                            break;
                        case 4:
                            $y = rand(1, 7);
                            break;
                    }
                    $x = rand(1, 10);
                }
                if ($type == 1) {
                    for($i = 0; $i < count($lock['H']); $i++){
                        for($k = 0; $k <= $height; $k++){
                            if($x + $k == $lock['H'][$i] && $y == $lock['V'][$i]){
                                $status = 1;
                            }
                        }
                    }
                }
                else {
                    for($i = 0; $i < count($lock['H']); $i++){
                        for($k = 0; $k <= $height; $k++){
                            if($x == $lock['H'][$i] && $y + $k == $lock['V'][$i]){
                                $status = 1;
                            }
                        }
                    }
                }

            } while ($status == 1);

        }



        if ($type == 1) {

            $this->location_v = "$y";
            $this->location_h = [];

            for ($i = 0; $i < $height; $i++) {
                $this->location_h[$i] = $x;
                $x++;
            }

            return "h";
        }
        elseif($type == 2){

            $this->location_h = "$x";
            $this->location_v = [];

            for($i = 0; $i < $height; $i++){
                $this->location_v[$i] = $y;
                $y++;
            }

            return "v";

        }
        else{
            $this->location_h = "$x";
            $this->location_v = "$y";

            return "solo";
        }

    }

    public function save_2($db, $type){


        $query = "insert into Ship (name, location_h, location_v, hp, type) values (:name, :location_h, :location_v, {$this->hp}, '{$type}')";
        $stmt = $db->prepare($query);


        $stmt->bindValue(":name",$this->name);

        if($type == "h"){
            $string = implode(' ', $this->location_h);
            $stmt->bindValue(":location_h",$string);
            $stmt->bindValue(":location_v",$this->location_v);
        }
        elseif($type == "v"){
            $string = implode(' ', $this->location_v);
            $stmt->bindValue(":location_v", $string);
            $stmt->bindValue(":location_h",$this->location_h);
        }
        else{
            $stmt->bindValue(":location_v",$this->location_v);
            $stmt->bindValue(":location_h",$this->location_h);
        }


        $num_row = $stmt->execute();


        if($num_row > 0){
            echo "Кораблю успішно збережено";
        }
        else{
            echo "Ship Save Error";
        }
    }


    public function setName($name){
        if (!empty($name)){
            $this->name = $name;
        }
        else{
            echo "Name Set Error";
        }
    }
    public function getName(){
        return $this->name;
    }
    public function setLocation($loc){
        $this->location = explode(" ", $loc);
    }
    public function getLocation(){
        return $this->location;
    }
    public function setLocation_h($loc){
        $this->location_h = explode(" ", $loc);
    }
    public function getLocation_h(){
        return $this->location_h;
    }
    public function setLocation_v($loc){
        $this->location_v = explode(" ", $loc);
    }
    public function getLocation_v(){
        return $this->location_v;
    }

    public function setHp($hp){
        $this->hp = $hp;
    }
    public function getHp(){
        return $this->hp;
    }

}