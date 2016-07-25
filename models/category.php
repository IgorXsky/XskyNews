<?php

class Category extends Model{

    public static function getListCategory(){

        $db = mysqli_connect(Config::get('db.host'), Config::get('db.user'), Config::get('db.password'), Config::get('db.db_name'));

        $sql = 'SELECT * FROM categories';

        $result = mysqli_query($db, $sql);
        return $result;
    }//список категорий


    public function save($data, $id = null){
        if ( !isset($data['alias']) || !isset($data['name'])){
            return false;
        }

        $id = (int)$id;
        $alias = $this->db->escape($data['alias']);
        $name = $this->db->escape($data['name']);
        $is_published = isset($data['is_published']) ? 1 : 0;

        if ( !$id ){ // Add new record
            $sql = "
                INSERT INTO pages
                   SET alias = '{$alias}',
                       name = '{$name}',
                       is_published = {$is_published}
            ";
        } else { // Update existing record
            $sql = "
                UPDATE pages
                   SET alias = '{$alias}',
                       name = '{$name}',
                       is_published = {$is_published}
                 WHERE id = {$id}
            ";
        }

        return $this->db->query($sql);
    }

    public function delete($id){
        $id = (int)$id;
        $sql = "DELETE FROM categories WHERE id = {$id}";
        return $this->db->query($sql);
    }


}