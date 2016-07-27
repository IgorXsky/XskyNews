<?php

class Comment extends Model{

    public function getCommentsList()
    {
        $sql = "SELECT * FROM comments
                ORDER BY `status`";

        return $this->db->query($sql);
    }


    public function getModerateList()
    {
        $sql = "SELECT * FROM comments
                WHERE category_id = 7
                ORDER BY `status`";

        return $this->db->query($sql);
    }


    public function addComment($data)
    {
        if (!isset($data['title']) || !isset($data['content']) || !isset($data['news_id'])){
            return false;
        }

        $user_id = $_SESSION['user_id'];
        $news_id = $this->db->escape($data['news_id']);;
        $title = $this->db->escape($data['title']);
        $content = $this->db->escape($data['content']);
        $category_id = $this->db->escape($data['category_id']);
        if ($category_id != 7) {
            $sql = "INSERT INTO comments (title, content, user_id, news_id, category_id)
                    VALUES ('{$title}', '{$content}', '{$user_id}', '{$news_id}', '{$category_id}')
                   ";
        } else {
            $sql = "INSERT INTO comments (title, content, user_id, news_id, category_id, is_active)
                    VALUES ('{$title}', '{$content}', '{'$user_id'}', '{$news_id}', '{$category_id}', 0)
                   ";
        }
        return $this->db->query($sql);
    }


    public function getCommentsListByUser($user_id, $page)
    {
        $page--;
        $offset = $page * COUNT_NEWS;

        $sql = "SELECT * FROM comments
                WHERE user_id = '{$user_id}'
                ORDER BY `likes` DESC
                LIMIT {$offset} , 5";

        return $this->db->query($sql);
    }



    public function getCountCommentsByUser($user_id)
    {
        $sql = "SELECT COUNT(*) AS count FROM comments
                WHERE user_id = '{$user_id}'";

        return $this->db->query($sql);
    }


    public function getCountCommentsByNews($news_id)
    {
        $sql = "SELECT COUNT(*) AS count
                FROM comments AS c
                JOIN articles AS a ON c.news_id = a.id
                WHERE a.id = '{$news_id}'";

        return $this->db->query($sql);
    }


    public function getCommentListByNews($news_id, $page)
    {
        $page--;
        $offset = $page * COUNT_NEWS;

        $sql = "SELECT c.id AS comment_id, c.title, c.content, c.likes, c.dislikes, a.id
                FROM comments AS c
                JOIN articles AS a ON c.news_id = a.id
                WHERE a.id = '{$news_id}'
                ORDER BY `date` DESC
                LIMIT {$offset} , 5";

        return $this->db->query($sql);
    }//список комментариев к новости



    public function getCommentById($id)
    {
        $sql = "SELECT c.id, c.title, c.content, c.user_id, c.likes, c.dislikes, a.id AS news_id
                FROM comments AS c
                JOIN articles AS a
                ON c.news_id = a.id
                WHERE c.id = '{$id}' ";

        return $this->db->query($sql);
    }

    public function addLike($id, $like){

        $sql = "UPDATE comments SET likes = '{$like}'
                WHERE id = '{$id}'
                ";

        return $this->db->query($sql);
    }


    public function addDislike($id, $dislike){

        $sql = "UPDATE comments SET dislikes = '{$dislike}'
                WHERE id = '{$id}'
                ";

        return $this->db->query($sql);
    }

    public function getTopUsers(){

        $sql = "SELECT u.id AS user_id, u.login, c.title, c.id, COUNT(*) AS total
                FROM users AS u
                JOIN comments AS c ON u.id = c.user_id
                GROUP BY login
                ORDER BY total DESC
                ";

        return $this->db->query($sql);

    }


    public function delete($id){
        $id = (int)$id;
        $sql = "DELETE FROM comments WHERE id = {$id}";
        return $this->db->query($sql);
    }


    public function edit($data, $id){
        if ( !isset($data['id'])){
            return false;
        }

        $id = (int)$id;
        $title = $this->db->escape($data['title']);
        $content = $this->db->escape($data['content']);
        $status = isset($data['status']) ? 1 : 0;

            $sql = "UPDATE comments
                    SET title = '{$title}',
                        content = '{$content}',
                        status = {$status}
                    WHERE id = {$id}
                   ";

        return $this->db->query($sql);
    }


    public function moderate($id){

        $id = (int)$id;

        $sql = "UPDATE comments
                    SET status = '1'
                    WHERE id = {$id}
                   ";

        return $this->db->query($sql);
    }

}


