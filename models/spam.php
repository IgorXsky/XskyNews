<?php

class Spam extends Model{

    public static function getListRight(){

        $db = mysqli_connect(Config::get('db.host'), Config::get('db.user'), Config::get('db.password'), Config::get('db.db_name'));

        $sql = "SELECT * FROM spam ORDER BY id LIMIT 4";

        $result = mysqli_query($db, $sql);

        return $result;

    }

    public static function getListLeft(){

        $db = mysqli_connect(Config::get('db.host'), Config::get('db.user'), Config::get('db.password'), Config::get('db.db_name'));

        $sql = "SELECT * FROM spam ORDER BY id DESC LIMIT 4";

        $result = mysqli_query($db, $sql);

        return $result;

    }
}