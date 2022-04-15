<?php

class AuthController extends Controller {

    public function index()
    {
        header('Location: ' . BASEURL . '/auth/login');
        exit;
    }

    public function login()
    {
        Auth::authRole();
        $data['page_title'] = 'Log In';
        $this->view('templates/header', $data);
        $this->view('auth/login');
        $this->view('templates/footer');
    }

    public function processLogin()
    {
        Auth::authRole();
        $user = $this->model('User')->getUserByCredentials($_POST);

        if( !empty($user) ) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user'] = $user['name'];
            $_SESSION['role'] = $user['role'];
        }
        else {
            Flasher::setFlash('Incorrect email/password!', 'danger');
        }
        header('Location: ' . BASEURL . '/auth/login');
        exit;
    }

    public function register()
    {
        Auth::authRole();
        $data['page_title'] = 'Register';
        $this->view('templates/header', $data);
        $this->view('auth/register');
        $this->view('templates/footer');
    }

    public function processRegister()
    {
        Auth::authRole();
        if ( $this->model('User')->getUsersWhere('email','=',$_POST['email']) ) {
            Flasher::setFlash('Email has been used!', 'danger');
            header('Location: ' . BASEURL . '/auth/register');
            exit;
        }
        else {
            $this->model('User')->addUser($_POST);
            Flasher::setFlash('Successfully registered! Please log in.', 'success');
            header('Location: ' . BASEURL . '/auth/login');
            exit;
        }
    }

    public function logout()
    {
        Auth::authRole(array("user","admin"));
        session_destroy();
        session_start();
        Flasher::setFlash('Logged out', 'warning');
        header('Location: ' . BASEURL . '/auth/login');
        exit;
    }
}