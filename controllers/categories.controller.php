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
        $this->data['slider'] = $this->model->getListLimit();
        $this->data['comments'] = $this->model->getCommentsList();
    }

    public function read(){
        $params = App::getRouter()->getParams();

        if (isset($params[0])) {
            $alias = strtolower($params[0]);
            $arr_num = $this->number = $this->model->getCountArticleByCategoryAlias($alias);
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
        $this->data['slider'] = $this->model->getListLimit();
        $this->data['comments'] = $this->model->getCommentsList();
    }

}