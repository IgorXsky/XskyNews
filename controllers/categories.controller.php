<?php

class CategoriesController extends Controller
{

    public function __construct($data = array())
    {
        parent::__construct($data);
        $this->model = new Category();
        $this->model = new Article();

    }

    public function index(){
        $this->data['categories'] = $this->model->getListCategory();
        $this->data['articles'] = $this->model->getList();
        $this->data['spam'] = $this->model->getListSpam();
        $this->data['slider'] = $this->model->getSliderNews();
        $this->data['comments'] = $this->model->getCommentsList();
    }

    public function read(){
        $params = App::getRouter()->getParams();

        if (isset($params[0])) {
            $alias = strtolower($params[0]);
            $arr_num = $this->number = $this->model->getTotalArticleInCategory($alias);
            //$pagination = new Pagination($arr_num,10, 5);
            $num = $arr_num['0']['count'];
            $this->data['pages'] = $pages = ceil($num/5);
            $this->data['category'] = $this->model->getArticleByCategoryAlias($alias);
        }
    }//Не польностю реализован, так как ещё думаю как передавать номер страницы пейджера по параметрам


    public function view()
    {
        $params = App::getRouter()->getParams();

        if (isset($params[0])) {
            $alias = strtolower($params[0]);
            $this->data['category'] = $this->model->getByAlias($alias);
        }
    }

    public function admin_index(){
        $this->data['categories'] = $this->model->getListCategory();
        $this->data['articles'] = $this->model->getList();
        $this->data['spam'] = $this->model->getListSpam();
        $this->data['slider'] = $this->model->getSliderNews();
        $this->data['comments'] = $this->model->getCommentsList();
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
                Session::setFlash('Category was saved.');
            } else {
                Session::setFlash('Error.');
            }
            Router::redirect('/admin/categories/');
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
                Session::setFlash('Category was deleted.');
            } else {
                Session::setFlash('Error.');
            }
        }
        Router::redirect('/admin/categories/');
    }

}