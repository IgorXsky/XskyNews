<?php

class Article extends Model{

    public function getListCategory(){
        $sql = "SELECT * FROM categories";
        return $this->db->query($sql);
    }//список категорий

    public function getListSpam(){
        $sql = "SELECT * FROM spam LiMIT 4";
        return $this->db->query($sql);
    }//рекламма

    public  function getArticleByCategoryAlias($alias){
        $sql = "SELECT a.title, a.content, a.al, a.photo, c.alias
                FROM articles AS a
                JOIN categories AS c
                ON a.category_id = c.id
                WHERE c.alias = '{$alias}'
               ";
        return $this->db->query($sql);
    }//список новостей по катеории

    public function getList(){
        $sql = "select * from articles ORDER BY `date` DESC";
        return $this->db->query($sql);
    }//список всех новостей

    public function getListLimit(){
        $sql = "select * from articles ORDER BY `date` DESC LIMIT 5";
        return $this->db->query($sql);
    }//список всех новостей

    public function getCountArticleByCategoryAlias($alias){
        $sql = "SELECT COUNT(*) AS count
                FROM articles AS a
                JOIN categories AS c
                ON a.category_id = c.id
                WHERE c.alias = '{$alias}'
               ";
        return $this->db->query($sql);
    }//количество новостей по катеории

    public function getArticleByCategoryAliasPaginator($alias, $page){
        $ofset = ($page * 5) - 5;
        $sql = "SELECT a.id, a.title, a.content, a.al, a.photo, c.alias
                FROM articles AS a
                JOIN categories AS c
                ON a.category_id = c.id
                WHERE c.alias = '{$alias}'
                LIMIT ". $ofset . "," . 5
               ;
        return $this->db->query($sql);
    }//список из 5 новостей, на странице


    public function getCommentsList(){
        $sql = "select * from comments ORDER BY `points` DESC LIMIT 5";
        return $this->db->query($sql);
    }//список из 5 новостей, на странице

    public function getByAlias($alias){
        $alias = $this->db->escape($alias);
        $sql = "select * from articles where al = '{$alias}' limit 1";
        $result = $this->db->query($sql);
        return isset($result[0]) ? $result[0] : null;
    }

    public function getById($id){
        $id = (int)$id;
        $sql = "select * from articles where id = '{$id}' limit 1";
        $result = $this->db->query($sql);
        return isset($result[0]) ? $result[0] : null;
    }

    public function addComment($data){
        if ( !isset($data['title']) || !isset($data['content']) ||!isset($data['news_id']) ){
            return false;
        }
        $id = $this->db->escape($data['news_id']);;
        $title = $this->db->escape($data['title']);
        $content = $this->db->escape($data['content']);
        $category_id = $this->db->escape($data['category_id']);
        if($category_id != 7) {
            $sql = "INSERT INTO comments (title, content, news_id, category_id)
                VALUES ('{$title}', '{$content}', '{$id}', '{$category_id}')
                   ";
        }else{
            $sql = "INSERT INTO comments (title, content, news_id, category_id, is_active)
                VALUES ('{$title}', '{$content}', '{$id}', '{$category_id}', 0)
                   ";
        }
        return $this->db->query($sql);
    }

    public function delete($id){
        $id = (int)$id;
        $sql = "delete from articles where id = {$id}";
        return $this->db->query($sql);
    }

}