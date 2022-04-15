<?php

class HomeController extends Controller {

    public function index()
    {
        $data['page_title'] = 'Home';

        $this->view('templates/header', $data);
        $this->view('home', $data);
        $this->view('templates/footer');
    }

    public function sendMessage()
    {
        $this->model('Message')->addMessage($_POST);

        header('Location: ' . BASEURL . '/');
        exit;
    }

}