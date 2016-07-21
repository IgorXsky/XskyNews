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

            $this->data['comments'] = $this->model->getCommentsListByUser($user_id);
        }
    }

}