<?php

class NewsController extends Controller
{
    public $ArticleModel;
    public $CategoryModel;
    public $CommentModel;
    public $SpamModel;


    public function __construct($data = array())
    {
        parent::__construct($data);
        $this->CategoryModel = new Category();
        $this->ArticleModel = new Article();
        $this->CommentModel = new Comment();
        $this->SpamModel = new Spam();

    }

    public function index(){
        $this->data['categories'] = $this->CategoryModel->getListCategory();
        $this->data['articles'] = $this->ArticleModel->getList();
        $this->data['spam'] = $this->SpamModel->getListSpam();
        $this->data['slider'] = $this->ArticleModel->getSliderNews();
        $this->data['comments'] = $this->CommentModel->getCommentsList();
    }

}