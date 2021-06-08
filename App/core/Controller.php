<?php

/*
 *
 * Base Controller
 * Loads the models and views
 *
 */
require_once '../App/vendor/autoload.php';
//require $_SERVER['DOCUMENT_ROOT'] . '/api-mvc-authorization/App/vendor/autoload.php';
//require_once 'vendor/firebase/php-jwt/src/JWT.php';

use \Firebase\JWT\JWT;

class Controller
{

    /**
     *
     * Controller constructor.
     *
     **/
    private $key = 'marniga_Token_halal';

    //load model
    protected function model($model)
    {
        // require model :
        require_once "../App/models/" . $model . '.php';
        return new $model();
    }

    public function authorization()
    {
        $iat = time();
        $exp = $iat + 60 * 60;
        $payload = array(
            "iss" => "localhost",
            "aud" => "localhost",
            "iat" => $iat,
            'exp' => $exp,

        );
        $jwt = JWT::encode($payload, $this->key, 'HS512');
        return $jwt;
    }

    public function gettoken()
    {
        $headers = apache_request_headers();
        if (isset($headers['authorization'])) {
            return str_replace('Bearer ', '', $headers['authorization']);
        } else {
            return false;
        }
    }

    public function verification($token)
    {
        return JWT::decode($token, $this->key, array('HS512'));
    }

}