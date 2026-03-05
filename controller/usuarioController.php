<?php

class usuarioController{
    private $model;
    public function __construct() 
    { 
        require_once(__DIR__ . '/../model/usuarioModel.php');
        $this->model = new usuarioModel();
    }

    public function insertar($identificacion, $nombres, $direccion, $telefono, $calificacion = null, $observacion = null){
        return $this->model->insertar($identificacion, $nombres, $direccion, $telefono, $calificacion, $observacion);
    }

    public function actualizarUsuario($id_persona, $identificacion, $nombres, $direccion, $telefono, $calificacion = null, $observacion = null){
        return $this->model->actualizar($id_persona, $identificacion, $nombres, $direccion, $telefono, $calificacion, $observacion);
    }

    public function eliminar($id_persona){
        return $this->model->eliminar($id_persona);
    }


    public function buscarId($id_persona){
        return $this->model->buscarId($id_persona);
    }
    
    public function listarTodos(){
        return $this->model->listarTodos();
    }
}

?>