<?php

class Article extends Model{

    public function getSliderNews() {

        $sql = "SELECT a.id, a.title, a.date, a.content, a.photo, c.name
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


    public  function getArticleByCategoryAlias($alias, $page){
        $page--;
        $offset = $page * COUNT_NEWS;


        $sql = "SELECT a.id, a.title, a.content, a.photo, c.alias
                FROM articles AS a
                JOIN categories AS c
                ON a.category_id = c.id
                WHERE c.alias = '{$alias}'
                LIMIT {$offset} , 5
               ";
        return $this->db->query($sql);
    }//список новостей по алиасу катеории


    public function getNewsByTags($tag_id){

        $sql = "SELECT * FROM articles AS a
                JOIN articles_tags AS at ON a.id = at.article_id
                WHERE at.tag_id = '{$tag_id}'
                ";
        return $this->db->query($sql);
    }//поиск новостей по id тегов


    public function searchTags($tag){

        $sql = "SELECT a.id, a.title, a.category_id, a.status, a.already_read, a.date, t.id AS tag_id, t.name
                FROM articles AS a
                JOIN articles_tags AS at ON at.article_id = a.id
                JOIN tags AS t ON at.tag_id = t.id
                WHERE t.name = '{$tag}'
                ";
        return $this->db->query($sql);
    }//поиск по тегам


    public function search($data){

        if(isset($data['category'])){
            $category = $data['category'];
        }
        if(!empty($data['dateStart'])){
            $dateStart = array_reverse(explode(".", $data['dateStart']));
            $dateStart = implode("-", $dateStart);
        }
        if(!empty($data['dateEnd'])){
            $dateEnd = array_reverse(explode(".", $data['dateEnd']));
            $dateEnd = implode("-", $dateEnd);
        }

            if(!isset($dateStart) && !isset($dateEnd)){

                if ($category != 0) {
                    $sql = "SELECT * FROM articles AS a
                                WHERE a.category_id = '{$category}'
                                ";
                } else {
                    $sql = "SELECT * FROM articles";
                }
                return $this->db->query($sql);
            }


            if(isset($dateStart) && isset($dateEnd)){

                if ($category != 0) {
                    $sql = "SELECT * FROM articles AS a
                            WHERE a.category_id = '{$category}'
                            AND a.date BETWEEN '{$dateStart}' AND '{$dateEnd}'
                    ";
                }else{
                    $sql = "SELECT * FROM articles AS a
                            WHERE a.date BETWEEN '{$dateStart}' AND '{$dateEnd}'
                    ";
                }
                return $this->db->query($sql);
            }


            if(!isset($dateStart) && isset($dateEnd)){

                if ($category != 0) {
                    $sql = "SELECT * FROM articles AS a
                            WHERE a.category_id = '{$category}'
                            AND a.date < '{$dateEnd}'
                            ";
                } else {
                    $sql = "SELECT * FROM articles AS a
                            WHERE a.date < '{$dateEnd}'
                            ";
                }
                return $this->db->query($sql);
            }


            if(isset($dateStart) && !isset($dateEnd)){

                if ($category != 0) {
                    $sql = "SELECT * FROM articles AS a
                            WHERE a.category_id = '{$category}'
                            AND a.date > '{$dateStart}'
                            ";
                } else {
                    $sql = "SELECT * FROM articles AS a
                            WHERE a.date > '{$dateStart}'
                            ";
                }
                return $this->db->query($sql);
            }

    }//расширенный поиск



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


    public function getCountArticleByCategoryAlias($alias){

        $sql = "SELECT COUNT(*) AS count
                        FROM articles AS a
                        JOIN categories AS c
                        ON a.category_id = c.id
                        WHERE c.alias = '{$alias}'
                       ";
        return $this->db->query($sql);
    }//количество новостей по катеории

    public function getCountArticleByTag($tag){

        $sql = "SELECT COUNT(a.title) AS count
                FROM articles AS a
                JOIN articles_tags AS at ON at.article_id = a.id
                JOIN tags AS t ON at.tag_id = t.id
                WHERE t.name = '{$tag}'
        ";

        return $this->db->query($sql);
    }//количество новостей по тегу



    /*
     * Admin functions
     */


    public function searhThisTag($tag){

        $sql = "SELECT t.id AS tag_id, t.name
                FROM tags AS t
                WHERE t.name = '{$tag}'
                ";
        return $this->db->query($sql);
    }


    public function new_tag($tag){

        $tag = $this->db->escape($tag);

        $sql = " INSERT INTO tags
                 SET name = '{$tag}'
               ";
        return $this->db->query($sql);
    }


    public function add_tag($id, $tag_id){
        $id = (int)$id;
        $tag_id = (int)$tag_id;
        $sql = " INSERT INTO articles_tags
                 SET article_id = {$id},
                     tag_id = {$tag_id}
               ";
        return $this->db->query($sql);
    }


    public function add_photo($save, $id){
        if( !isset($save)||
            !isset($id)){
            return false;
        }
        $id = (int)$id;
        $photo = $this->db->escape($save);

        $sql = " UPDATE articles
                 SET photo = '{$photo}'
                 WHERE id = {$id}
               ";
        return $this->db->query($sql);

    }


    public function save($data, $save = null, $id = null){
        if ( !isset($data['title']) ||
             !isset($data['content']) ||
             !isset($data['category_id']) ||
             !isset($data['date'])){
            return false;
        }

        $id = (int)$id;
        $title = $this->db->escape($data['title']);
        $content = $this->db->escape($data['content']);
        $category_id = $this->db->escape($data['category_id']);
        $date = array_reverse(explode(".", $data['date']));
        $date = implode("-", $date);
        $photo = $this->db->escape($save);

        $status = isset($data['status']) ? 1 : 0;

        if ( !$id ){ // Add new record
            $sql = "
                INSERT INTO articles
                   SET title = '{$title}',
                       content = '{$content}',
                       category_id = '{$category_id}',
                       `date` = '{$date}',
                       status = {$status}
            ";
        } else { // Update existing record
            $sql = "
                UPDATE articles
                   SET title = '{$title}',
                       content = '{$content}',
                       category_id = '{$category_id}',
                       `date` = '{$date}',
                       photo = '{$photo}',
                       status = '{$status}'
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