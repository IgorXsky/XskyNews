<?php

class ArticlesController extends Controller{

    public $ArticleModel;
    public $CommentModel;
    public $TagsModel;

    public function __construct($data = array()){
        parent::__construct($data);

        $this->ArticleModel = new Article();
        $this->CommentModel = new Comment();
        $this->TagsModel = new Tags();
    }

    public function read(){

        $params = App::getRouter()->getParams();

        if ( isset($params[0]) ){
            $id = (int)($params[0]);

            $this->data['article'] = $this->ArticleModel->getById($id);
            $this->data['article']['read_now'] = $rand_count = rand(1, 5);
            $new_count = $rand_count + $this->data['article']['already_read'];
            $this->ArticleModel->setCountReadArticle($id, $new_count);
            $news_id = $this->data['article']['id'];
            $this->data['comments'] = $this->CommentModel->getCommentListByNews($news_id);
            $this->data['tags'] = $this->TagsModel->getTagsList($id);
        }
    }


    public function show(){
        $params = App::getRouter()->getParams();

        if (isset($params[0])) {
            $id = ($params[0]);
            //$arr_num = $this->number = $this->ArticleModel->getTotalArticleInCategory($alias);
            //$pagination = new Pagination($arr_num,10, 5);
            //$num = $arr_num['0']['count'];
            //$this->data['pages'] = $pages = ceil($num/5);
            $this->data['articles'] = $this->ArticleModel->getNewsByTags($id);
        }
    }


    public function comment(){

        $params = App::getRouter()->getParams();

        if (isset($params[0])) {
            $id = ($params[0]);

            $this->data['comment'] = $this->ArticleModel->getById($id);

            if ($_POST) {
                $result = $this->CommentModel->addComment($_POST);
                if ($result) {
                    Session::setFlash('Comment was saved.');
                } else {
                    Session::setFlash('Error.');
                }
                Router::redirect("/articles/read/{$id}");
            }
        }

    }


    public function like(){

        $params = App::getRouter()->getParams();

        if (isset($params[0])) {
            $id = ($params[0]);
            $this->data['comment'] = $this->CommentModel->getCommentById($id);

            $news_id = $this->data['comment'][0]['news_id'];
            $like = 1 + ($this->data['comment'][0]['likes']);

            $this->CommentModel->addLike($id, $like);

            Router::redirect("/articles/read/{$news_id}");
        }

    }


    public function dislike(){

        $params = App::getRouter()->getParams();

        if (isset($params[0])) {
            $id = ($params[0]);
            $this->data['comment'] = $this->CommentModel->getCommentById($id);

            $news_id = $this->data['comment'][0]['news_id'];
            $dislike = 1 + ($this->data['comment'][0]['dislikes']);

            $this->CommentModel->addDislike($id, $dislike);

            Router::redirect("/articles/read/{$news_id}");
        }

    }



    public function admin_index(){
        $this->data['articles'] = $this->model->getList();
    }

    public function admin_add(){
        if ( $_POST ){
            $result = $this->model->save($_POST);
            if ( $result ){
                Session::setFlash('Page was saved.');
            } else {
                Session::setFlash('Error.');
            }
            Router::redirect('/admin/articles/');
        }
    }

    public function admin_edit(){

        if ( $_POST ){
            $id = isset($_POST['id']) ? $_POST['id'] : null;
            $result = $this->model->save($_POST, $id);
            if ( $result ){
                Session::setFlash('Article was saved.');
            } else {
                Session::setFlash('Error.');
            }
            Router::redirect('/admin/articles/');
        }

        if ( isset($this->params[0]) ){
            $this->data['page'] = $this->model->getById($this->params[0]);
        } else {
            Session::setFlash('Wrong page id.');
            Router::redirect('/admin/articles/');
        }
    }

    public function admin_delete(){
        if ( isset($this->params[0]) ){
            $result = $this->model->delete($this->params[0]);
            if ( $result ){
                Session::setFlash('Article was deleted.');
            } else {
                Session::setFlash('Error.');
            }
        }
        Router::redirect('/admin/articles/');
    }

}