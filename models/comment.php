<?php

class Comment extends Model{

    public function addComment($data)
    {
        if (!isset($data['title']) || !isset($data['content']) || !isset($data['news_id'])) {
            return false;
        }
        $id = $this->db->escape($data['news_id']);;
        $title = $this->db->escape($data['title']);
        $content = $this->db->escape($data['content']);
        $category_id = $this->db->escape($data['category_id']);
        if ($category_id != 7) {
            $sql = "INSERT INTO comments (title, content, news_id, category_id)
                    VALUES ('{$title}', '{$content}', '{$id}', '{$category_id}')
                   ";
        } else {
            $sql = "INSERT INTO comments (title, content, news_id, category_id, is_active)
                    VALUES ('{$title}', '{$content}', '{$id}', '{$category_id}', 0)
                   ";
        }
        return $this->db->query($sql);
    }

    public function getCommentsList()
    {
        $sql = "SELECT * FROM comments ORDER BY `likes` DESC LIMIT 5";
        return $this->db->query($sql);
    }

    public function getCommentListByNews($news_id)
    {

        $sql = "SELECT c.title, c.content, a.id FROM comments AS c
                JOIN articles AS a ON c.news_id = a.id
                WHERE a.id = '{$news_id}'
                ORDER BY `date` DESC";

        return $this->db->query($sql);
    }


    public function setLikes($news_id)
    {

        $sql = "SELECT likes FROM comments
                WHERE news_id = {$news_id}
                ";

        return $this->db->query($sql);
    }

    public function setDislikes($news_id)
    {

        $sql = "SELECT dislikes FROM comments
                WHERE news_id = {$news_id}
                ";

        return $this->db->query($sql);
    }
}


