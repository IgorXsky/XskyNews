<?php

class User extends Model {

    public function getById($id){
        $id = (int)$id;
        $sql = "select * from users where id = '{$id}' limit 1";
        $result = $this->db->query($sql);
        if ( isset($result[0]) ){
            return $result[0];
        }
        return false;
    }

    public function getByLogin($login){
        $login = $this->db->escape($login);
        $sql = "select * from users where login = '{$login}' limit 1";
        $result = $this->db->query($sql);
        if ( isset($result[0]) ){
            return $result[0];
        }
        return false;
    }

    public function register($data){

        if( !isset($data['login']) || !isset($data['email']) || !isset($data['password'])){
            return false;
        }
        $login = $this->db->escape(strtolower($data['login']));
        $email = $this->db->escape(strtolower($data['email']));

        if( $this->getByLogin($login)){
            return false; // login alredy exist
        }

        $password = $data['password'];
        $hash = md5(Config::get('salt').$password);

        $sql = "INSERT INTO users
                SET
                    login = '{$login}',
                    email = '{$email}',
                    role = 'registered',
                    password = '{$hash}',
                    is_active = '1'
                    ";
        if ( $this->db->query($sql) ){
            return $this->db->insertId();
        }

        return false;

    }
}