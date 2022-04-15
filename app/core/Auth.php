<?php

class Auth {

    // Redirect if role does not match
    public static function authRole($roles = [])
    {
        if ( empty($_SESSION['role']) AND empty($roles) ){
            return;
        }
        foreach ($roles as $role) {
            if ( $_SESSION['role'] == $role) {
                return;
            }
        }
        header('Location: ' . BASEURL . '/');
        exit;
    }

    // Check if role is admin
    public static function isAdmin()
    {
        if( $_SESSION['role'] == 'admin' ) {
            return true;
        }
        return false;
    }

    // Check if role is user
    public static function isUser()
    {
        if( $_SESSION['role'] == 'user' ) {
            return true;
        }
        return false;
    }

    // Check if guest
    public static function isGuest()
    {
        if( empty($_SESSION['role']) ) {
            return true;
        }
        return false;
    }

    public static function userId()
    {
        return $_SESSION['user_id'];
    }
}