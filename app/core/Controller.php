<?php

class Controller
{
    public function view($view, $data = [])
    {
        require_once '../app/views/' . $view . '.phtml';
    }

    public function model($model)
    {
        require_once '../app/models/' . $model . '.php';
        return new $model;
    }

    public function validate($rules, $data)
    {
        $valid = true;
        $validator = new Validator();

        foreach ($rules as $var => $rule) {

            $rule = explode("|", $rule);

            foreach ($rule as $req) {

                $req = explode(":", $req);
                $error = "";

                switch ($req[0]) {
                    case 'required':
                        $error = $validator->required($var, $data[$var]);
                        break;

                    case 'integer':
                        $error = $validator->integer($var, $data[$var]);
                        break;

                    case 'image':
                        $error = $validator->image($var, $data[$var]);
                        break;

                    case 'minlength':
                        $error = $validator->minlength($var, $data[$var], $req[1]);
                        break;

                    case 'maxlength':
                        $error = $validator->maxlength($var, $data[$var], $req[1]);
                        break;

                    case 'confirmPassword':
                        $error = $validator->confirmPassword($data['password'], $data['confirm_password']);
                        break;
                }

                if (!empty($error)) {
                    Validator::setError($var, $error);
                    $valid = false;
                }
            }
        }

        return $valid;
    }
}
