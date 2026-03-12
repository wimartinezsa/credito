
<?php
require_once("../../controller/sociedadController.php");
require_once("../../controller/autenticacionController.php");
$controller_sociedad = new sociedadController();
$controller_autenticacion = new autenticacionController();

session_start();
if(isset($_SESSION["token"])){
        $usuario = $controller_autenticacion->validarToken($_SESSION['token']);
        if (!json_encode($usuario) && !strlen(json_encode($usuario)) > 0) {
    // echo json_encode($usuario );
    http_response_code(401);
    echo json_encode(['status' => 'error', 'message' => 'Token inválido o expirado']);
    exit;
}
} else {
    http_response_code(401);
    echo json_encode(['status' => 'error', 'message' => 'Token no proporcionado']);
    exit;
}
        



// Token válido, procesar solicitud


if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    $id_sociedad = $_GET['id_sociedad'] ?? null;
    if (!$id_sociedad) {
        http_response_code(400);
        echo json_encode(["error" => "ID de sociedad no proporcionado"]);
        exit;
    }


    $resultado = $controller_sociedad->disponibleSociedad( $id_sociedad );
    echo json_encode($resultado);
}


?>