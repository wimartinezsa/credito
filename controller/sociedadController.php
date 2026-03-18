<?php

class sociedadController{
    private $model;
    public function __construct() 
    { 
        require_once(__DIR__ . '/../model/sociedadModel.php');
        $this->model = new sociedadModel();
    }

    public function listarSociedadesEncargados(){
        return $this->model->listarSociedadesEncargados();
    }
    
    public function registrarSociedades($nombre, $valor){
        return $this->model->registrarSociedades($nombre, $valor);
    }   

    public function buscarSociedad($id){
        return $this->model->buscarSociedad($id);
    }   

    public function actualizarSociedad($id, $nombre, $valor){
        return $this->model->actualizarSociedad($id, $nombre, $valor);
    }   

    public function disponibleSociedad($id_sociedad){
        return $this->model->disponibleSociedad($id_sociedad);
    }




}

?>
