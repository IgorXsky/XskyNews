<?php

class CommentsController extends Controller{

    public function __construct($data = array()){
        parent::__construct($data);

        $this->model = new Comment();
    }

    public function show(){

        $params = App::getRouter()->getParams();

        if (isset($params[0])) {
            $user_id = ($params[0]);


            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $total = $this->model->getCountCommentsByUser($user_id);

            $this->data['p'] = new Pagination(array(
                'itemsCount' => $total[0]['count'],
                'itemsPerPage' => COUNT_NEWS,
                'currentPage' => $page
            ));

            $this->data['comments'] = $this->model->getCommentsListByUser($user_id, $page);

            $this->data['spam_left'] = Spam::getListLeft();
            $this->data['spam_right'] = Spam::getListRight();
        }
    }

    public function admin_index(){
        $this->data['comments'] = $this->model->getCommentsList();
        $this->data['on_moderate'] = $this->model->getModerateList();

    }


    public function admin_edit(){

        if ( $_POST ){
                $params = App::getRouter()->getParams();
                $id = $params[0];
            $result = $this->model->edit($_POST, $id);
            if ( $result ){
                Session::setFlash('Comment was saved.');
            } else {
                Session::setFlash('Error.');
            }
            Router::redirect('/admin/comments/');
        }

        if ( isset($this->params[0]) ){
            $this->data['comment'] = $this->model->getCommentById($this->params[0]);
        } else {
            Session::setFlash('Wrong category id.');
            Router::redirect('/admin/comments/');
        }
    }


    public function admin_ok(){

            $params = App::getRouter()->getParams();
            $id = $params[0];
            $result = $this->model->moderate($id);
            if ( $result ){
                Session::setFlash('Comment activated.');
            } else {
                Session::setFlash('Error.');
            }
            Router::redirect('/admin/comments/');
    }


    public function admin_delete(){
        if ( isset($this->params[0]) ){
            $result = $this->model->delete($this->params[0]);
            if ( $result ){
                Session::setFlash('Comment was deleted.');
            } else {
                Session::setFlash('Error.');
            }
        }
        Router::redirect('/admin/comments/');
    }


}