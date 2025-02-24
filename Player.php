<?php

class Player
{

    private $name;
    private $ammo;

    public function __construct($name, $ammo){

        $this->setName($name);
        $this->setAmmo($ammo);

    }

    public function show(){
        return "NickName: <b>".$this->getName()."</b> Зробив <b>".$this->getAmmo()."</b> Пострілів";
    }

    public function shoot($arr){

        $this->ammo += 1;

        $db = new PDO("mysql:host=localhost;dbname=ShipFight",'root','');
        $query = "update Player set ammo=ammo+1";
        $db->query($query);


        return $arr;
    }

    public function save($db){

        $query = "insert into Player (name, ammo) values('{$this->name}',{$this->ammo})";
        $db->query($query);

        echo "Користувач успішно Збережений";

    }


    public function setName($name){
        if (!empty($name)){
            $this->name=$name;
        }
        else{
            echo "Name Set Error";
        }
    }
    public function getName(){
        return $this->name;
    }
    public function setAmmo($ammo){
        $this->ammo = $ammo;
    }
    public function getAmmo(){
        return $this->ammo;
    }


}