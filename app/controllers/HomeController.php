<?php

class HomeController extends Controller
{

    public function index()
    {
        $data['page_title'] = 'Home';

        $this->view('templates/header', $data);
        $this->view('home', $data);
        $this->view('templates/footer');
    }

    public function contact_us()
    {
        $data['page_title'] = 'Contact Us';

        $this->view('templates/header', $data);
        $this->view('contact_us', $data);
        $this->view('templates/footer');
    }

    public function sendMessage()
    {
        $valid = $this->validate(
            [
                "subject" => "required",
                "name" => "required",
                "email" => "required",
                "message" => "required"
            ],
            $_POST
        );

        if ($valid == true) {
            $this->model('Message')->addMessage($_POST);
        }

        header('Location: ' . BASEURL . '/home/contact_us');
        exit;
    }
}
