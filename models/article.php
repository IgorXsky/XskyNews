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
    } // для слайдера, последние новости с каждой категории


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
        $sql = "SELECT * FROM articles ORDER BY `date` DESC";
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


    public function getTotalArticleInCategory($alias){
        $sql = "SELECT COUNT(*) AS count
                FROM articles AS a
                JOIN categories AS c
                ON a.category_id = c.id
                WHERE c.alias = '{$alias}'
               ";
        return $this->db->query($sql);
    }

    public function getByAlias($alias){
        $alias = $this->db->escape($alias);
        $sql = "SELECT * FROM articles WHERE al = '{$alias}' limit 1";
        $result = $this->db->query($sql);
        return isset($result[0]) ? $result[0] : null;
    }

    public function getById($id){
        $id = (int)$id;
        $sql = "SELECT * FROM articles WHERE id = '{$id}' limit 1";
        $result = $this->db->query($sql);
        return isset($result[0]) ? $result[0] : null;
    }


    /*
     *
     * Admin functions
     *
     *
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


    /*
     *
     * Comment functions
     *
     *
    */


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

    public function getCommentsList(){
        $sql = "SELECT * FROM comments ORDER BY `likes` DESC LIMIT 5";
        return $this->db->query($sql);
    }

    public function getCommentListByNews($news_id){

        $sql = "SELECT c.title, c.content, a.id FROM comments AS c
                JOIN articles AS a ON c.news_id = a.id
                WHERE a.id = '{$news_id}'
                ORDER BY `date` DESC";

        return $this->db->query($sql);
    }


    public function setLikes($news_id){

        $sql = "SELECT likes FROM comments
                WHERE news_id = {$news_id}
                ";

        return $this->db->query($sql);
    }

    public function setDislikes($news_id){

        $sql = "SELECT dislikes FROM comments
                WHERE news_id = {$news_id}
                ";

        return $this->db->query($sql);
    }

}