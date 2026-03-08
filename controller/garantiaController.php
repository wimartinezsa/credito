<?php

class garantiaController{

    private $model;
    public function __construct() 
    { 
        require_once(__DIR__ . '/../model/garantiaModel.php');
        $this->model = new garantiaModel();
    }


    public function subirGarantia(){
        if(isset($_FILES['archivo'])){

            $prestamo = $_POST['prestamo_id'];
            $tipo = $_POST['tipo'];

            $archivo = $_FILES['archivo']['name'];
            $tmp = $_FILES['archivo']['tmp_name'];

            $directorio = "../uploads/garantias/";

            $nombreFinal = time()."_".$archivo;

            $ruta = $directorio.$nombreFinal;

            move_uploaded_file($tmp,$ruta);

            $this->model->registrarGarantia($tipo,$ruta,$prestamo);

            echo "Documento subido correctamente";
        }

    }

    function listarTipoGarantia(){
        return $this->model->listarTipoGarantia();
    }

     function listarGarantiasPrestamo($prestamo_id){
        return $this->model->listarGarantiasPrestamo($prestamo_id); 
     }


    function eliminarGarantia($id_garantia){
        $this->model->eliminarGarantia($id_garantia);

        }

}


?>