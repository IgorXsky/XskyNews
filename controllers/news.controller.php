<?php

class NewsController extends Controller
{
    public $ArticleModel;
    public $CategoryModel;
    public $CommentModel;
    public $SpamModel;
    public $TagsModel;

    public function __construct($data = array())
    {
        parent::__construct($data);
        $this->TagsModel = new Tags();
        $this->CategoryModel = new Category();
        $this->ArticleModel = new Article();
        $this->CommentModel = new Comment();
        $this->SpamModel = new Spam();

    }

    public function index(){

        $this->data['tags'] = Tags::getAllTags();
        $this->data['categories'] = Category::getListCategory();
        $this->data['articles'] = $this->ArticleModel->getList();
        $this->data['spam'] = $this->SpamModel->getListSpam();
        $this->data['slider'] = $this->ArticleModel->getSliderNews();
        $this->data['top_users'] = $this->CommentModel->getTopUsers();
        $this->data['top_news'] = $this->ArticleModel->getTopNews();

    }


    public function search(){

        if ( $_GET && isset($_GET['tag']) ){
            $tag = $_GET['tag'];
            $this->data['search'] = $this->ArticleModel->searchTags($tag);
            print_r($_POST);
        }
        if($_POST){
            $this->data['search'] = $this->ArticleModel->search($_POST);
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



}