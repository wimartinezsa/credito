<?php

    require_once("../../model/garantiaModel.php");


 if(isset($_FILES['archivo'])){

           $prestamo = $_POST['codigo_prestamo'];
           $tipo = $_POST['tipo'];

if(empty($prestamo)) {
    echo "Error: Debe seleccionar un préstamo.";
    return;
}

if(!isset($_FILES['archivo'])){
    echo "Debe seleccionar un archivo";
    return;
}

$archivo = $_FILES['archivo']['name'];
$tmp = $_FILES['archivo']['tmp_name'];

$directorio = "../../uploads/garantias/";

$nombreFinal = time()."_".$archivo;

$ruta = $directorio.$nombreFinal;

/* subir archivo */
if(move_uploaded_file($tmp,$ruta)){

    $modelo = new garantiaModel();

    $modelo->registrarGarantia($tipo,$ruta,$prestamo);

    echo "Documento subido correctamente";

}else{

    echo "Error al subir el archivo";

}
}

?>