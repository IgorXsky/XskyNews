<?php

class NewsController extends Controller
{
    public $ArticleModel;
    public $CategoryModel;
    public $CommentModel;
    public $TagsModel;

    public function __construct($data = array())
    {
        parent::__construct($data);
        $this->TagsModel = new Tags();
        $this->CategoryModel = new Category();
        $this->ArticleModel = new Article();
        $this->CommentModel = new Comment();

    }

    public function index(){

        $this->data['tags'] = Tags::getAllTags();
        $this->data['categories'] = Category::getListCategory();
        $this->data['articles'] = $this->ArticleModel->getList();
        $this->data['spam_right'] = Spam::getListRight();
        $this->data['spam_left'] = Spam::getListLeft();
        $this->data['slider'] = $this->ArticleModel->getSliderNews();
        $this->data['top_users'] = $this->CommentModel->getTopUsers();
        $this->data['top_news'] = $this->ArticleModel->getTopNews();

    }


    public function search(){

        if ( $_GET && isset($_GET['tag']) ){
            $tag = $_GET['tag'];
            $this->data['search'] = $this->ArticleModel->searchTags($tag);
        }
        if($_POST){

            $this->data['search'] = $this->ArticleModel->search($_POST);

        }
        $this->data['spam_left'] = Spam::getListLeft();
        $this->data['spam_right'] = Spam::getListRight();
    }


    public function admin_index(){

        $this->data['tags'] = Tags::getAllTags();
        $this->data['categories'] = Category::getListCategory();
        $this->data['articles'] = $this->ArticleModel->getList();
        $this->data['top_users'] = $this->CommentModel->getTopUsers();
        $this->data['top_news'] = $this->ArticleModel->getTopNews();

    }



}