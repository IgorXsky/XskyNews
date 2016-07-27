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

            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $total = $this->CommentModel->getCountCommentsByNews($news_id);

            $this->data['p'] = new Pagination(array(
                'itemsCount' => $total[0]['count'],
                'itemsPerPage' => COUNT_NEWS,
                'currentPage' => $page
            ));

            $this->data['comments'] = $this->CommentModel->getCommentListByNews($news_id, $page);
            $this->data['tags'] = $this->TagsModel->getTagsList($id);
            $this->data['spam_left'] = Spam::getListLeft();
            $this->data['spam_right'] = Spam::getListRight();
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
        $this->data['articles'] = $this->ArticleModel->getList();
    }


    public function admin_add(){

        if ( $_POST ){

            $result = $this->ArticleModel->save($_POST);
            if ( $result ){
                Session::setFlash('Page was saved.');
            } else {

                Session::setFlash('Error');
            }
            Router::redirect('/admin/articles/');
        }
    }

    public function admin_add_photo(){

        if ($_POST){
            $params = App::getRouter()->getParams();
            $id = $params[0];
            if ($_FILES && isset($_FILES['photo'])) {
                $tmp_name = $_FILES['photo']['tmp_name'];
                $name = $id.'.jpg';
                $save = '/webroot/uploads/'.$name;
                $path_save = UPLOADS.DS.$name;
                move_uploaded_file($tmp_name, $path_save);
            }
            $result = $this->ArticleModel->add_photo($save, $id);
            if ($result) {
                Session::setFlash('Photo was saved.');
            } else {
                Session::setFlash('Error.');
            }
            Router::redirect('/admin/articles/');
        }
    }

    public function admin_add_tag(){
        if ($_POST) {
            $params = App::getRouter()->getParams();
            $id = $params[0];
            $tag= ($_POST['tag']);

            $search = $this->ArticleModel->searhThisTag($tag);
            print_r($search);
            if($search){
                $result = $this->ArticleModel->add_tag($id,$search[0]['tag_id']);
                if ($result) {
                    Session::setFlash('Tag was add.');
                } else {
                    Session::setFlash('Error.');
                }
            }else{
                $this->ArticleModel->new_tag($tag);
                $new_search = $this->ArticleModel->searhThisTag($tag);
                $result = $this->ArticleModel->add_tag($new_search);
                if ($result) {
                    Session::setFlash('Tag was saved.');
                } else {
                    Session::setFlash('Error.');
                }
            }

            Router::redirect('/admin/articles/');
        }
    }

    public function admin_edit(){

        if ( $_POST ){
            $params = App::getRouter()->getParams();
            $id = $params[0];

            if($_FILES && isset($_FILES['photo'])){
                $tmp_name = $_FILES['photo']['tmp_name'];
                $name = $id.'.jpg';
                $save = '/webroot/uploads/'.$name;
                $path_save = UPLOADS.DS.$name;
                move_uploaded_file($tmp_name,$path_save);
            }
            $result = $this->ArticleModel->save($_POST, $save, $id);
            if ( $result ){
                Session::setFlash('Article was saved.');
            } else {
                Session::setFlash('Error.');
            }
            Router::redirect('/admin/articles/');
        }

        if ( isset($this->params[0]) ){
            $this->data['article'] = $this->ArticleModel->getById($this->params[0]);
        } else {
            Session::setFlash('Wrong page id.');
            Router::redirect('/admin/articles/');
        }
    }

    public function admin_delete(){
        if ( isset($this->params[0]) ){
            $result = $this->ArticleModel->delete($this->params[0]);
            if ( $result ){
                Session::setFlash('Article was deleted.');
            } else {
                Session::setFlash('Error.');
            }
        }
        Router::redirect('/admin/articles/');
    }

}