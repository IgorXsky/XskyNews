<?php

class UsersController extends Controller{

    public function __construct($data = array()){
        parent::__construct($data);
        $this->model = new User();
    }

    public function register(){
        if( $_POST ){
            $result = $this->model->register($_POST);
            if ($result) {
                $user = $this->model->getById($result);
                Session::set('user_id', $user['id']);
                Session::set('login', $user['login']);
                Session::set('role', $user['role']);
                Session::setFlash('Вы зарегистрированы');
                Router::redirect('/');
            }else{
                Session::setFlash('Ошибка при регистрации');
                Router::redirect('/users/login');
            }
        }
    }

    public function login(){
        if ( $_POST && isset($_POST['login']) && isset($_POST['password']) ){
            $user = $this->model->getByLogin($_POST['login']);
            $hash = md5(Config::get('salt').$_POST['password']);
            if ( $user && $user['is_active'] && $hash == $user['password'] ){
                Session::set('user_id', $user['id']);
                Session::set('login', $user['login']);
                Session::set('role', $user['role']);
            }
            if ( $user['role'] == 'admin'){
                Router::redirect('/admin/');
            }
            Router::redirect('/');
        }
    }

    public function logout(){
        Session::destroy();
        Router::redirect('/');
    }


}