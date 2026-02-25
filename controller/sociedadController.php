<?php

class sociedadController{
    private $model;
    public function __construct() 
    { 
        require_once(__DIR__ . '/../model/sociedadModel.php');
        $this->model = new sociedadModel();
    }

    public function listarSociedades(){
        return $this->model->listarSociedades();
    }
    
    
}

?>
