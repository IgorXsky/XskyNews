<?php

class Tags extends Model{

    public function getTagsList($id){

        $sql = "SELECT t.id AS tag_id, t.name, at.article_id
                FROM `tags` AS t
                JOIN articles_tags AS at ON t.id = at.tag_id
                WHERE at.article_id = '{$id}'
                ";
        return $this->db->query($sql);
    }//поиск тегов по id новостей

    public static function getAllTags(){

        $db = mysqli_connect(Config::get('db.host'), Config::get('db.user'), Config::get('db.password'), Config::get('db.db_name'));

        $sql = 'SELECT t.id, t.name FROM `tags` AS t';

        $result = mysqli_query($db, $sql);
        return $result;
    }//вывод всех тегов


}