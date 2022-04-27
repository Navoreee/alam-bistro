<?php

class Validator
{

    public function __construct()
    {
    }

    public static function setError($var, $error)
    {
        $_SESSION['error'][$var] = $error;
    }

    public static function showError($var)
    {
        if (isset($_SESSION['error'][$var])) {
            echo '<p>' . $_SESSION['error'][$var] . '</p>';
            unset($_SESSION['error'][$var]);
        }
    }

    public function required($var, $data)
    {
        if (empty($data)) {
            $error = ucfirst($var) . " is required";
            return $error;
        }
    }

    public function integer($var, $data)
    {
        if (!empty($data) && filter_var($data, FILTER_VALIDATE_INT) == false) {
            $error = ucfirst($var) . " must be integer";
            return $error;
        }
    }

    public function image($var, $data)
    {
        $ext = array("jpg", "jpeg", "png");

        if (in_array($data, $ext) == false) {
            $error = ucfirst($var) . " must be of type .jpg, .jpeg, or .png";
            return $error;
        }
    }

    public function minlength($var, $data, $minlength)
    {
        if (!empty($data) && strlen($data) < $minlength) {
            $error = ucfirst($var) . " must be at least " . $minlength . " characters";
            return $error;
        }
    }

    public function maxlength($var, $data, $maxlength)
    {
        if (!empty($data) && strlen($data) > $maxlength) {
            $error = ucfirst($var) . " must not be more than " . $maxlength . " characters";
            return $error;
        }
    }

    public function confirmPassword($pwd1, $pwd2)
    {
        if ($pwd1 != $pwd2) {
            $error = "Password confirmation does not match";
            return $error;
        }
    }
}
