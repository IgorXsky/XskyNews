<?php

class CategoriesController extends Controller
{
    public $ArticleModel;
    public $CategoryModel;
    //public $SpamModel;



    public function __construct($data = array())
    {
        parent::__construct($data);
        $this->CategoryModel = new Category();
        $this->ArticleModel = new Article();
        //$this->SpamModel = new Spam();

    }

    public function index(){
        $params = App::getRouter()->getParams();

        if (isset($params[0])) {
            $alias = strtolower($params[0]);
            $this->data['category'] = $this->ArticleModel->getArticleByCategoryAlias($alias);
        }
    }//Не польностю реализован, так как ещё думаю как передавать номер страницы пейджера по параметрам


/*
    public function read()
    {
        $params = App::getRouter()->getParams();

        if (isset($params[0])) {
            $alias = strtolower($params[0]);
            $this->data['category'] = $this->ArticleModel->getByAlias($alias);
        }
    }
*/
    public function admin_index(){
        $this->data['categories'] = Category::getListCategory();
    }


    public function admin_add(){

        if ( $_POST ){
            $result = $this->CategoryModel->save($_POST);
            if( $result ){
                Session::setFlash('Category was saved.');
            }else{
                Session::setFlash('Error.');
            }

            Router::redirect('/admin/categories/');
        }
    }

    public function admin_edit(){

        if ( $_POST ){
            $id = isset($_POST['id']) ? $_POST['id'] : null;
            $result = $this->CategoryModel->save($_POST, $id);
            if ( $result ){
                Session::setFlash('Category was saved.');
            } else {
                Session::setFlash('Error.');
            }
            Router::redirect('/admin/categories/');
        }

        if ( isset($this->params[0]) ){
            $this->data['category'] = $this->CategoryModel->getById($this->params[0]);
        } else {
            Session::setFlash('Wrong category id.');
            Router::redirect('/admin/categories/');
        }
    }

    public function admin_delete(){
        if ( isset($this->params[0]) ){
            $result = $this->CategoryModel->delete($this->params[0]);
            if ( $result ){
                Session::setFlash('Category was deleted.');
            } else {
                Session::setFlash('Error.');
            }
        }
        Router::redirect('/admin/categories/');
    }

}