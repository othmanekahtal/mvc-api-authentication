<?php

/*
 *
 * Base Controller
 * Loads the models and views
 *
 */

class Controller
{

    /**
     *
     * Controller constructor.
     *
     **/

    //load model
    protected function model($model)
    {
        // require model :
        require_once "../App/models/" . $model . '.php';
        return new $model();
    }
}