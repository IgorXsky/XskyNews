<?php

class Spam extends Model
{

    public function getListSpam()
    {
        $sql = "SELECT * FROM spam";
        return $this->db->query($sql);
    }//рекламма
}