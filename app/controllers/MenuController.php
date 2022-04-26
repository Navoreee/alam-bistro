<?php

class MenuController extends Controller {

    public function index()
    {
        $data['categories'] = $this->model('Category')->getAllCategories();

        $data['page_title'] = 'Menu';

        $this->view('templates/header', $data);
        $this->view('menu/index', $data);
        $this->view('templates/footer');
    }

    public function category($id)
    {
        $data['category'] = $this->model('Category')->getCategoryById($id);
        $data['items'] = $this->model('MenuItem')->getItemsWhere('category_id','=',$id);
        
        $data['page_title'] = 'Menu';

        $this->view('templates/header', $data);
        $this->view('menu/category', $data);
        $this->view('templates/footer');
    }

    public function search($keyword)
    {
        $data['items'] = $this->model('MenuItem')->getItemsWhere('name','LIKE',"%$keyword%");

        $data['page_title'] = 'Menu';

        $this->view('templates/header', $data);
        $this->view('menu/index', $data);
        $this->view('templates/footer');
    }
}