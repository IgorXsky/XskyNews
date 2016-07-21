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

}