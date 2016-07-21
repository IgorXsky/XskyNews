<?php

class Article extends Model{

    public function getSliderNews() {

        $sql = "SELECT a.id, a.title, a.date, a.content, a.photo, a.al, c.name
				FROM articles AS a
				JOIN categories AS c ON a.category_id = c.id
				WHERE a.status = 1
				GROUP BY c.name
				ORDER BY `date` DESC";

        return $this->db->query($sql);
    }  //слайдер



    public function getList(){
        $sql = "SELECT * FROM articles ORDER BY `date` DESC";
        return $this->db->query($sql);
    }//список всех новостей


    public function getById($id){
        $id = (int)$id;
        $sql = "SELECT * FROM articles WHERE id = '{$id}' limit 1";
        $result = $this->db->query($sql);
        return isset($result[0]) ? $result[0] : null;
    }// выбрать категорию по id


    public function getByAlias($alias){
        $alias = $this->db->escape($alias);
        $sql = "SELECT * FROM articles WHERE al = '{$alias}' limit 1";
        $result = $this->db->query($sql);
        return isset($result[0]) ? $result[0] : null;
    }//выбрать новость по алиасу


    public  function getArticleByCategoryAlias($alias){
        $sql = "SELECT a.id, a.title, a.content, a.photo, c.alias
                FROM articles AS a
                JOIN categories AS c
                ON a.category_id = c.id
                WHERE c.alias = '{$alias}'
               ";
        return $this->db->query($sql);
    }//список новостей по алиасу катеории


    public function getNewsByTags($tag_id){

        $sql = "SELECT * FROM articles AS a
                JOIN articles_tags AS at ON a.id = at.article_id
                WHERE at.tag_id = '{$tag_id}'
                ";
        return $this->db->query($sql);
    }//поиск новостей по тегам


    public function setCountReadArticle($id, $new_count){

        $sql = "UPDATE articles SET already_read = '{$new_count}'
                WHERE id = '{$id}'
                  ";

        return $this->db->query($sql);
    }//обновляем значение $count++


    public function getTopNews(){

        $sql = "SELECT a.id, a.title, a.date, a.content, a.photo, COUNT(*) AS total
                FROM articles AS a
                JOIN comments AS c ON a.id = c.news_id
                GROUP BY a.id
                ORDER BY total DESC
                LIMIT 3
                ";
        return $this->db->query($sql);

    }//топ 3 новости


    /* для пейджера

    public function getCountArticleByCategoryAlias($alias){
        $sql = "SELECT COUNT(*) AS count
                FROM articles AS a
                JOIN categories AS c
                ON a.category_id = c.id
                WHERE c.alias = '{$alias}'
               ";
        return $this->db->query($sql);
    }//количество новостей по катеории

    */








    /*
     * Admin functions
     */

    public function save($data, $id = null){
        if ( !isset($data['alias']) ||
             !isset($data['title']) ||
             !isset($data['content']) ||
             !isset($data['category_id']) ||
             !isset($data['date']) ||
             !isset($data['photo'])){
            return false;
        }

        $id = (int)$id;
        $alias = $this->db->escape($data['alias']);
        $title = $this->db->escape($data['title']);
        $content = $this->db->escape($data['content']);
        $category_id = $this->db->escape($data['category_id']);
        $date = $this->db->escape($data['date']);
        $photo = $this->db->escape($data['photo']);

        $is_published = isset($data['is_published']) ? 1 : 0;

        if ( !$id ){ // Add new record
            $sql = "
                INSERT INTO articles
                   SET alias = '{$alias}',
                       title = '{$title}',
                       content = '{$content}',
                       categoty_id = '{$category_id}',
                       `date` = '{$date}',
                       photo = '{$photo}',
                       is_published = {$is_published}
            ";
        } else { // Update existing record
            $sql = "
                UPDATE articles
                   SET alias = '{$alias}',
                       title = '{$title}',
                       content = '{$content}',
                       categoty_id = '{$category_id}',
                       `date` = '{$date}',
                       photo = '{$photo}',
                       is_published = {$is_published}
                 WHERE id = {$id}
            ";
        }

        return $this->db->query($sql);
    }


    public function delete($id){
        $id = (int)$id;
        $sql = "DELETE FROM articles WHERE id = {$id}";
        return $this->db->query($sql);
    }


}