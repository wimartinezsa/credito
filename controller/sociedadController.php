<?php

class sociedadController{
    private $model;
    public function __construct() 
    { 
        require_once(__DIR__ . '/../model/sociedadModel.php');
        $this->model = new sociedadModel();
    }

    public function listarTodasSociedades(){
        return $this->model->listarTodasSociedades();
    }
    
 public function listarSociedadesEncargados(){
        return $this->model->listarSociedadesEncargados();
    }

    public function listarEncargadosSociedadesId($id_sociedad){
        return $this->model->listarEncargadosSociedadesId($id_sociedad);
    }
    



    public function registrarSociedades($nombre, $valor){
        return $this->model->registrarSociedades($nombre, $valor);
    }   

    public function buscarSociedad($id){
        return $this->model->buscarSociedad($id);
    }   

    public function adicionarSociedad($id, $nombre, $valor){
        return $this->model->adicionarSociedad($id, $nombre, $valor);
    }   

    public function disponibleSociedad($id_sociedad){
        return $this->model->disponibleSociedad($id_sociedad);
    }

public function listarPerosnasEncargados(){
        return $this->model->listarPerosnasEncargados();
    }


public function asignarEncargadoSociedad( $id_sociedad, $encargado, $rol){
        return $this->model->asignarEncargadoSociedad( $id_sociedad, $encargado, $rol);
    }



    public function   eliminarEncargadoSociedad($id_admin){
        return $this->model->  eliminarEncargadoSociedad($id_admin);
    }

  




}

?>
